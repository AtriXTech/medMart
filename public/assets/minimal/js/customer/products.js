const productsError = document.getElementById('products-error');
const productsLoading = document.getElementById('products-loading');
const productsGrid = document.getElementById('products-grid');
const searchInput = document.getElementById('product-search');

let searchTimeout = null;
let cartProductIds = [];

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
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

function renderProducts(products) {
    productsGrid.innerHTML = '';
    
    if (!products || products.length === 0) {
        productsGrid.innerHTML = '<div class="empty-state" style="grid-column: 1/-1;">No products found</div>';
        return;
    }
    
    products.forEach(function(product) {
        const isInCart = cartProductIds.includes(product.id);
        
        const card = document.createElement('div');
        card.style.cssText = 'background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;';
        
        card.innerHTML = `
            <div style="width: 100%; height: 120px; background: var(--bg); display: flex; align-items: center; justify-content: center; font-size: 40px; cursor: pointer;" onclick="window.location.href='/customer/products/${product.id}'">
                📦
            </div>
            <div style="padding: 12px;">
                <div style="font-weight: 600; font-size: 13px; margin-bottom: 4px; cursor: pointer;" onclick="window.location.href='/customer/products/${product.id}'">${product.name}</div>
                <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">${product.category || 'N/A'}</div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong style="color: var(--primary);">${formatCurrency(product.price)}</strong>
                    ${product.in_stock 
                        ? '<span class="badge badge-success">In Stock</span>'
                        : '<span class="badge badge-danger">Out</span>'
                    }
                </div>
                ${product.in_stock ? `
                    ${isInCart 
                        ? `<button class="btn btn-secondary btn-block" disabled style="font-size: 12px; padding: 6px;">✓ In Cart</button>`
                        : `<button class="btn btn-primary btn-block" id="quick-add-${product.id}" style="font-size: 12px; padding: 6px;">Add to Cart</button>`
                    }
                ` : ''}
            </div>
        `;
        
        productsGrid.appendChild(card);
        
        if (product.in_stock && !isInCart) {
            const quickAddBtn = document.getElementById(`quick-add-${product.id}`);
            if (quickAddBtn) {
                quickAddBtn.addEventListener('click', async function(event) {
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
                        quickAddBtn.className = 'btn btn-secondary btn-block';
                        quickAddBtn.disabled = true;
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
        }
    });
}

async function loadProducts() {
    if (!CustomerAuth.requireAuth()) return;
    
    productsLoading.style.display = 'block';
    productsError.style.display = 'none';
    productsGrid.style.display = 'none';
    
    const params = new URLSearchParams();
    params.append('per_page', 50);
    
    if (searchInput.value.trim()) {
        params.append('search', searchInput.value.trim());
    }
    
    try {
        const data = await CustomerApi.get(`/customer/products?${params.toString()}`);
        renderProducts(data.data || data);
        productsLoading.style.display = 'none';
        productsGrid.style.display = 'grid';
    } catch (error) {
        productsLoading.style.display = 'none';
        productsError.textContent = error.message || 'Unable to load products.';
        productsError.style.display = 'block';
    }
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