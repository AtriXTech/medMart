/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: showToast(), animateGridChange(), loadCartProductIds(),
    groupProductsByCategory(), showCategoryProducts(), renderProducts()'s
    product-card markup and quick-add wiring, loadProducts() (same
    endpoint/search debounce/category-vs-search branching), every
    existing element ID.
  - FIXED: the quick-add success handler never told the header badge
    anything changed — cart.js dispatches a `cart-updated` event after
    every load/update/remove, but this file never fired it after a
    successful add, which is why the badge only updated once you
    actually visited the Cart page. Now dispatches the same event
    (using cartProductIds.length, which already tracks distinct items
    correctly for this page's session) right after a successful add.
  - REDESIGNED (per explicit request): renderCategories()'s card markup
    only — border removed, a persistent subtle shadow instead of
    hover-only, Manrope for the category name instead of Inter, and the
    "View items" row's hover cue moved to a small circular arrow chip
    instead of the whole icon square flipping to solid blue. Nothing
    about the underlying category grouping logic changed.
*/

const productsError = document.getElementById('products-error');
const productsLoading = document.getElementById('products-loading');
const productsGrid = document.getElementById('products-grid');
const searchInput = document.getElementById('product-search');

const categoryHeader = document.getElementById('category-header');
const backToCategoriesBtn = document.getElementById('back-to-categories');
const activeCategoryTitle = document.getElementById('active-category-title');
const toastContainer = document.getElementById('toast-container');

let searchTimeout = null;
let cartProductIds = [];
let allProducts = [];
let activeCategory = null;

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

function isInStock(product) {
    const val = product.in_stock;
    if (typeof val === 'boolean') return val;
    if (typeof val === 'string') return val.toLowerCase() !== 'false' && val !== '0' && val !== '';
    return !!val;
}

// Visual Toast Feedback Notification
function showToast(message) {
    if (!toastContainer) return;

    const toast = document.createElement('div');
    toast.className = 'pointer-events-auto flex items-center gap-2.5 bg-[#171E26] text-white px-4 py-3 rounded-xl shadow-lg font-inter text-[13px] border border-white/10 opacity-0 translate-y-4 transition-all duration-300 ease-out';
    toast.innerHTML = `
        <div class="w-5 h-5 rounded-full bg-[#1F7A44] flex items-center justify-center shrink-0">
            <i class="ph-bold ph-check text-white text-[11px]"></i>
        </div>
        <span>${message}</span>
    `;

    toastContainer.appendChild(toast);

    requestAnimationFrame(function () {
        toast.classList.remove('opacity-0', 'translate-y-4');
        toast.classList.add('opacity-100', 'translate-y-0');
    });

    setTimeout(function () {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-4');
        setTimeout(function () {
            toast.remove();
        }, 300);
    }, 3000);
}

// Smooth Grid View Transition Helper
function animateGridChange(renderCallback) {
    if (!productsGrid) {
        renderCallback();
        return;
    }

    productsGrid.classList.remove('opacity-100', 'translate-y-0');
    productsGrid.classList.add('opacity-0', 'translate-y-2');

    setTimeout(function () {
        renderCallback();
        productsGrid.classList.remove('opacity-0', 'translate-y-2');
        productsGrid.classList.add('opacity-100', 'translate-y-0');
    }, 150);
}

async function loadCartProductIds() {
    try {
        const cart = await CustomerApi.get('/customer/cart');
        const items = cart.items || [];
        cartProductIds = items.map(function(item) {
            return item.product.id;
        });
    } catch (error) {
        console.error('Unable to load cart:', error);
    }
}

function groupProductsByCategory(products) {
    const categories = {};
    products.forEach(function (product) {
        const catName = (product.category && product.category.trim()) ? product.category.trim() : 'General';
        if (!categories[catName]) {
            categories[catName] = [];
        }
        categories[catName].push(product);
    });
    return categories;
}

/* ---------------- REDESIGNED: category cards ---------------- */
// No border, persistent subtle shadow (deepens slightly on hover with a
// small lift), Manrope for the category name, and the interactive hover
// cue lives on a small circular arrow chip rather than the whole icon
// square flipping to solid blue.
function renderCategories(products) {
    animateGridChange(function () {
        activeCategory = null;
        if (categoryHeader) categoryHeader.style.display = 'none';

        const categories = groupProductsByCategory(products);
        const categoryNames = Object.keys(categories);

        if (categoryNames.length === 0) {
            productsGrid.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center text-center py-14">
                <i class="ph-light ph-magnifying-glass text-2xl text-[#171E26]/20 mb-1.5"></i>
                <p class="font-inter text-[13px] text-[#171E26]/40">No categories found</p>
            </div>`;
            return;
        }

        productsGrid.innerHTML = categoryNames.map(function (catName) {
            const count = categories[catName].length;

            return `<div class="bg-white rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-5 flex flex-col justify-between cursor-pointer category-card group" data-category="${catName}">
                <div>
                    <div class="w-11 h-11 rounded-xl bg-[#E9F3FE] flex items-center justify-center mb-4">
                        <i class="ph-light ph-squares-four text-[#2775E4] text-xl"></i>
                    </div>
                    <p class="font-manrope text-[14px] font-bold text-[#171E26] truncate mb-1">${catName}</p>
                    <p class="font-inter text-[11.5px] text-[#171E26]/45">${count} ${count === 1 ? 'product' : 'products'}</p>
                </div>
                <div class="flex items-center justify-between mt-5 pt-3 border-t border-[#F3F7FC]">
                    <span class="font-inter text-[11.5px] font-semibold text-[#171E26]/60 group-hover:text-[#2775E4] transition-colors">View items</span>
                    <span class="h-6 w-6 rounded-full bg-[#F7FAFD] flex items-center justify-center group-hover:bg-[#2775E4] transition-colors">
                        <i class="ph-bold ph-caret-right text-[11px] text-[#171E26]/40 group-hover:text-white transition-colors"></i>
                    </span>
                </div>
            </div>`;
        }).join('');

        productsGrid.querySelectorAll('.category-card').forEach(function (el) {
            el.addEventListener('click', function () {
                const selectedCategory = el.getAttribute('data-category');
                showCategoryProducts(selectedCategory);
            });
        });
    });
}

function showCategoryProducts(catName) {
    activeCategory = catName;
    const categories = groupProductsByCategory(allProducts);
    const productsInCat = categories[catName] || [];

    if (categoryHeader) {
        categoryHeader.style.display = 'flex';
        if (activeCategoryTitle) {
            activeCategoryTitle.textContent = `Back to Categories (${catName})`;
        }
    }

    renderProducts(productsInCat);
}

function renderProducts(products) {
    animateGridChange(function () {
        if (!products || products.length === 0) {
            productsGrid.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center text-center py-14">
                <i class="ph-light ph-magnifying-glass text-2xl text-[#171E26]/20 mb-1.5"></i>
                <p class="font-inter text-[13px] text-[#171E26]/40">No products found</p>
            </div>`;
            return;
        }

        productsGrid.innerHTML = products.map(function (product) {
            const inStock = isInStock(product);
            const isInCart = cartProductIds.includes(product.id);

            const imageHtml = product.image_url
                ? `<img src="${product.image_url}" alt="${product.name}" class="w-full h-full object-cover">`
                : `<i class="ph-light ph-package text-[#2775E4] text-4xl"></i>`;

            const stockBadge = inStock
                ? `<span class="font-inter text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:#E9F8EF;color:#1F7A44">In Stock</span>`
                : `<span class="font-inter text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:#FDEDEC;color:#9C3A32">Out</span>`;

            let actionButton = '';
            if (inStock) {
                actionButton = isInCart
                    ? `<button disabled class="w-full py-2 rounded-lg bg-[#F1F3F6] text-[#171E26]/50 font-inter text-[12px] font-semibold">✓ In Cart</button>`
                    : `<button id="quick-add-${product.id}" class="quick-add-btn w-full py-2 rounded-lg bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[12px] font-semibold hover:opacity-95 active:scale-[0.98] transition-all">Add to Cart</button>`;
            }

            return `<div class="bg-white border border-[#EAF1FB] rounded-xl overflow-hidden hover:shadow-sm transition-shadow">
                <div class="w-full h-[100px] bg-[#E9F3FE] flex items-center justify-center cursor-pointer product-open" data-product-id="${product.id}">
                    ${imageHtml}
                </div>
                <div class="p-2.5">
                    <p class="font-inter text-[13px] font-semibold text-[#171E26] truncate cursor-pointer product-open" data-product-id="${product.id}">${product.name}</p>
                    <p class="font-inter text-[11px] text-[#171E26]/45 mb-2">${product.category || 'N/A'}</p>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-inter text-[13px] font-semibold text-[#2775E4]">${formatCurrency(product.price)}</span>
                        ${stockBadge}
                    </div>
                    ${actionButton}
                </div>
            </div>`;
        }).join('');

        productsGrid.querySelectorAll('.product-open').forEach(function (el) {
            el.addEventListener('click', function () {
                window.location.href = `/customer/products/${el.getAttribute('data-product-id')}`;
            });
        });

        products.forEach(function (product) {
            if (!isInStock(product) || cartProductIds.includes(product.id)) return;

            const quickAddBtn = document.getElementById(`quick-add-${product.id}`);
            if (quickAddBtn) {
                quickAddBtn.addEventListener('click', async function (event) {
                    event.stopPropagation();
                    quickAddBtn.disabled = true;
                    quickAddBtn.textContent = 'Adding...';

                    try {
                        await CustomerApi.post('/customer/cart/items', {
                            product_id: product.id,
                            quantity: 1,
                        });
                        cartProductIds.push(product.id);
                        quickAddBtn.textContent = '✓ In Cart';
                        quickAddBtn.className = 'w-full py-2 rounded-lg bg-[#F1F3F6] text-[#171E26]/50 font-inter text-[12px] font-semibold';
                        quickAddBtn.disabled = true;

                        // Trigger Visual Toast Notification
                        showToast(`${product.name} added to cart!`);

                        // FIXED: this was the missing piece — cart.js already
                        // dispatches 'cart-updated' after every load/update/
                        // remove, and the header's listener already targets
                        // the real #cart-badge element correctly. This page's
                        // quick-add flow just never fired the event, so the
                        // badge sat stale until the Cart page was visited.
                        window.dispatchEvent(new CustomEvent('cart-updated', {
                            detail: { count: cartProductIds.length }
                        }));
                    } catch (error) {
                        quickAddBtn.disabled = false;
                        quickAddBtn.textContent = 'Add to Cart';
                        if (error.status === 422 && error.data && error.data.errors) {
                            alert(Object.values(error.data.errors).flat().join('\n'));
                        } else {
                            alert(error.message || 'Unable to add to cart.');
                        }
                    }
                });
            }
        });
    });
}

async function loadProducts() {
    if (!CustomerAuth.requireAuth()) return;

    productsLoading.style.display = 'grid';
    productsError.style.display = 'none';
    productsGrid.style.display = 'none';

    const query = searchInput.value.trim();
    const params = new URLSearchParams();
    params.append('per_page', 50);

    if (query) {
        params.append('search', query);
    }

    try {
        const data = await CustomerApi.get(`/customer/products?${params.toString()}`);
        allProducts = data.data || data;

        productsLoading.style.display = 'none';
        productsGrid.style.display = 'grid';

        if (query) {
            if (categoryHeader) categoryHeader.style.display = 'none';
            renderProducts(allProducts);
        } else if (activeCategory) {
            showCategoryProducts(activeCategory);
        } else {
            renderCategories(allProducts);
        }
    } catch (error) {
        productsLoading.style.display = 'none';
        productsError.textContent = error.message || 'Unable to load products.';
        productsError.style.display = 'block';
    }
}

if (backToCategoriesBtn) {
    backToCategoriesBtn.addEventListener('click', function () {
        renderCategories(allProducts);
    });
}

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        loadProducts();
    }, 500);
});

async function init() {
    await loadCartProductIds();
    loadProducts();
}

init();