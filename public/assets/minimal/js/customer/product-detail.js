const productError = document.getElementById('product-error');
const productLoading = document.getElementById('product-loading');
const productContent = document.getElementById('product-content');

const pathParts = window.location.pathname.split('/');
const productId = pathParts[pathParts.length - 1];

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

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
        
        productContent.innerHTML = `
            <div class="card">
                <div style="width: 100%; height: 200px; background: var(--bg); display: flex; align-items: center; justify-content: center; font-size: 64px; border-radius: var(--radius); margin-bottom: 16px;">
                    📦
                </div>
                <h2 style="margin: 0 0 4px 0;">${product.name}</h2>
                ${product.generic_name ? `<p style="color: var(--text-muted); margin: 0 0 8px 0;">${product.generic_name}</p>` : ''}
                <p style="font-size: 24px; color: var(--primary); margin: 0 0 12px 0;">${formatCurrency(product.price)}</p>
                ${product.description ? `<p style="margin: 0 0 12px 0;">${product.description}</p>` : ''}
                <div style="margin-bottom: 16px;">
                    <span class="badge ${product.in_stock ? 'badge-success' : 'badge-danger'}">
                        ${product.in_stock ? 'In Stock' : 'Out of Stock'}
                    </span>
                    ${product.requires_prescription ? '<span class="badge badge-warning">Requires Prescription</span>' : ''}
                </div>
                ${product.in_stock ? `
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="number" id="quantity" value="1" min="1" style="width: 80px; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                        <button class="btn btn-primary" id="add-to-cart-btn" style="flex: 1;">Add to Cart</button>
                    </div>
                ` : ''}
            </div>
        `;
        
        productLoading.style.display = 'none';
        productContent.style.display = 'block';
        
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