/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: productId extraction from window.location.pathname, the
    redirect-to-/customer/products guard, loadProduct() (GET
    /customer/products/:id, same loading/content/error toggle), the
    add-to-cart button's behavior (POST /customer/cart/items, same
    quantity parsing, same redirect to /customer/cart on success, same
    422 error handling, same disabled/"Adding..." state).
  - FIXED: the image container now actually uses product.image_url when
    present (confirmed real field, same ProductResource schema as the
    products list) instead of always showing a static package icon.
  - FIXED: added isInStock() to defensively handle in_stock being typed as
    a STRING in the API spec rather than a boolean — same fix already
    applied to the customer products list page, same underlying field.
  - ADDED: clicking the product image (only when image_url exists) opens
    #image-modal as a zoomed lightbox. Closable via the X button or
    clicking the backdrop. Wired once at load time since the modal is
    static markup in the Blade file, not re-created per product load.
*/

const productError = document.getElementById('product-error');
const productLoading = document.getElementById('product-loading');
const productContent = document.getElementById('product-content');
const imageModal = document.getElementById('image-modal');
const imageModalImg = document.getElementById('image-modal-img');
const closeImageModalBtn = document.getElementById('close-image-modal-btn');

const pathParts = window.location.pathname.split('/');
const productId = pathParts[pathParts.length - 1];

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

// The API spec types in_stock as a STRING, not boolean. If it's ever sent as
// the literal string "false", a plain truthy check (product.in_stock) would
// incorrectly treat it as in-stock, since any non-empty string is truthy in
// JS. Same fix as the customer products list page, same underlying field.
function isInStock(product) {
    const val = product.in_stock;
    if (typeof val === 'boolean') return val;
    if (typeof val === 'string') return val.toLowerCase() !== 'false' && val !== '0' && val !== '';
    return !!val;
}

function openImageModal(url, alt) {
    imageModalImg.src = url;
    imageModalImg.alt = alt || '';
    imageModal.style.display = 'flex';
}

function closeImageModal() {
    imageModal.style.display = 'none';
}

closeImageModalBtn.addEventListener('click', closeImageModal);
imageModal.addEventListener('click', function(event) {
    if (event.target === imageModal) {
        closeImageModal();
    }
});

async function loadProduct() {
    if (!CustomerAuth.requireAuth()) return;
    if (!productId || productId === 'products') {
        window.location.href = '/customer/products';
        return;
    }

    productLoading.style.display = 'block';
    productContent.style.display = 'none';
    productError.style.display = 'none';

    try {
        const product = await CustomerApi.get(`/customer/products/${productId}`);
        const inStock = isInStock(product);

        const stockBadge = inStock
            ? `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:#E9F8EF;color:#1F7A44">In Stock</span>`
            : `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:#FDEDEC;color:#9C3A32">Out of Stock</span>`;

        const rxBadge = product.requires_prescription
            ? `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full ml-1.5" style="background:#FFF8EC;color:#8A6116">Requires Prescription</span>`
            : '';

        const purchaseControls = inStock ? `
            <div class="mt-5">
                <label for="quantity" class="block font-inter text-[13px] font-medium text-[#171E26] mb-1.5">Quantity</label>
                <div class="flex items-center gap-2.5">
                    <input type="number" id="quantity" value="1" min="1"
                           class="w-20 h-12 flex-shrink-0 rounded-xl border border-[#DBEBFB] px-3 text-center font-inter text-[14px] text-[#171E26] focus:outline-none focus:ring-2 focus:ring-[#2775E4] focus:border-[#2775E4] transition">
                    <button id="add-to-cart-btn"
                            class="flex-1 h-12 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[14px] shadow-md shadow-[#2775E4]/20 disabled:opacity-60 hover:opacity-95 transition">
                        Add to Cart
                    </button>
                </div>
            </div>
        ` : '';

        const imageHtml = product.image_url
            ? `<img id="product-image" src="${product.image_url}" alt="${product.name}" class="w-full h-full object-cover cursor-zoom-in">
               <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-[#171E26]/20 transition">
                   <i class="ph-fill ph-magnifying-glass-plus text-white text-2xl"></i>
               </div>`
            : `<i class="ph-light ph-package text-[#2775E4] text-6xl"></i>`;

        productContent.innerHTML = `
            <div class="rounded-2xl bg-white border border-[#EAF1FB] p-4 md:p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div id="product-image-wrap" class="relative w-full md:w-[280px] h-[220px] flex-shrink-0 rounded-xl bg-[#E9F3FE] flex items-center justify-center overflow-hidden ${product.image_url ? 'cursor-zoom-in' : ''}">
                        ${imageHtml}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-manrope font-extrabold text-[22px] text-[#171E26]">${product.name}</h2>
                        ${product.generic_name ? `<p class="font-inter text-[13px] text-[#171E26]/45 mt-0.5">${product.generic_name}</p>` : ''}
                        <p class="font-manrope font-extrabold text-[26px] text-[#2775E4] mt-3">${formatCurrency(product.price)}</p>
                        ${product.description ? `<p class="font-inter text-[14px] text-[#171E26]/70 mt-3 leading-relaxed">${product.description}</p>` : ''}
                        <div class="mt-4">
                            ${stockBadge}${rxBadge}
                        </div>
                        ${purchaseControls}
                    </div>
                </div>
            </div>
        `;

        productLoading.style.display = 'none';
        productContent.style.display = 'block';

        if (product.image_url) {
            document.getElementById('product-image-wrap').addEventListener('click', function() {
                openImageModal(product.image_url, product.name);
            });
        }

        const addToCartBtn = document.getElementById('add-to-cart-btn');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', async function() {
                const quantity = parseInt(document.getElementById('quantity').value) || 1;
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Adding...';

                try {
                    await CustomerApi.post('/customer/cart/items', {
                        product_id: product.id,
                        quantity: quantity,
                    });
                    window.location.href = '/customer/cart';
                } catch (error) {
                    if (error.status === 422 && error.data && error.data.errors) {
                        alert(Object.values(error.data.errors).flat().join('\n'));
                    } else {
                        alert(error.message || 'Unable to add to cart.');
                    }
                } finally {
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = 'Add to Cart';
                }
            });
        }
    } catch (error) {
        productLoading.style.display = 'none';
        productError.textContent = error.message || 'Unable to load product.';
        productError.style.display = 'block';
    }
}

loadProduct();