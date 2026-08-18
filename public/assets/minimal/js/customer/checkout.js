const checkoutError = document.getElementById('checkout-error');
const checkoutLoading = document.getElementById('checkout-loading');
const checkoutContent = document.getElementById('checkout-content');

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

async function loadCheckout() {
    if (!CustomerAuth.requireAuth()) return;
    
    checkoutLoading.style.display = 'block';
    checkoutContent.style.display = 'none';
    checkoutError.style.display = 'none';
    
    try {
        const cart = await CustomerApi.get('/customer/cart');
        const items = cart.items || [];
        
        if (items.length === 0) {
            window.location.href = '/customer/cart';
            return;
        }
        
        const prescriptionProducts = items.filter(function(item) {
            return item.product.requires_prescription === true;
        });
        
        let hasApprovedPrescription = false;
        
        if (prescriptionProducts.length > 0) {
            const prescriptions = await CustomerApi.get('/customer/prescriptions?per_page=50');
            const prescriptionList = prescriptions.data || prescriptions;
            
            hasApprovedPrescription = prescriptionList.some(function(p) {
                return p.status === 'approved';
            });
        }
        
        const needsPrescription = prescriptionProducts.length > 0 && !hasApprovedPrescription;
        
        let itemsHtml = '';
        items.forEach(function(item) {
            itemsHtml += `
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border);">
                    <div>
                        <strong>${item.product.name}</strong>
                        <div style="font-size: 12px; color: var(--text-muted);">${item.quantity} × ${formatCurrency(item.product.price)}</div>
                    </div>
                    <div style="text-align: right;">
                        <strong>${formatCurrency(item.line_total)}</strong>
                        ${item.product.requires_prescription 
                            ? '<div><span class="badge badge-warning" style="font-size: 10px;">Rx Required</span></div>'
                            : ''
                        }
                    </div>
                </div>
            `;
        });
        
        let prescriptionSectionHtml = '';
        
        if (needsPrescription) {
            let prescriptionProductsList = '';
            prescriptionProducts.forEach(function(item) {
                prescriptionProductsList += `<li>${item.product.name}</li>`;
            });
            
            prescriptionSectionHtml = `
                <div class="card" style="margin-bottom: 16px; border-left: 3px solid var(--warning);">
                    <p class="section-title">⚠️ Prescription Required</p>
                    <p style="font-size: 13px; margin: 0 0 8px 0;">The following products require an approved prescription:</p>
                    <ul style="margin: 0 0 16px 0; padding-left: 20px; font-size: 13px;">
                        ${prescriptionProductsList}
                    </ul>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px;">Upload Prescription</label>
                        <input type="file" id="prescription-file" accept=".jpg,.jpeg,.png,.pdf" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                    </div>
                    <button class="btn btn-secondary btn-block" id="upload-prescription-btn">Upload Prescription</button>
                    <div id="prescription-upload-status" style="margin-top: 8px; font-size: 13px;"></div>
                </div>
            `;
        } else if (prescriptionProducts.length > 0 && hasApprovedPrescription) {
            prescriptionSectionHtml = `
                <div class="alert alert-success" style="margin-bottom: 16px;">
                    ✅ Prescription approved! You can proceed with checkout.
                </div>
            `;
        }
        
        checkoutContent.innerHTML = `
            ${prescriptionSectionHtml}
            
            <div class="card" style="margin-bottom: 16px;">
                <p class="section-title">Order Summary</p>
                ${itemsHtml}
                <div style="display: flex; justify-content: space-between; margin-top: 16px; padding-top: 16px; border-top: 2px solid var(--border);">
                    <strong>Total:</strong>
                    <strong style="font-size: 20px; color: var(--primary);">${formatCurrency(cart.total)}</strong>
                </div>
            </div>
            
            <div class="card">
                <p class="section-title">Fulfillment Method</p>
                <form id="checkout-form">
                    <div class="field">
                        <label for="fulfillment-type">Choose Method</label>
                        <select id="fulfillment-type" required>
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    <div class="field" id="delivery-address-field" style="display: none;">
                        <label for="delivery-address">Delivery Address</label>
                        <textarea id="delivery-address" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" id="checkout-submit" ${needsPrescription ? 'disabled' : ''}>
                        ${needsPrescription ? 'Upload Prescription First' : 'Place Order & Pay'}
                    </button>
                </form>
            </div>
        `;
        
        checkoutLoading.style.display = 'none';
        checkoutContent.style.display = 'block';
        
        const fulfillmentType = document.getElementById('fulfillment-type');
        const deliveryAddressField = document.getElementById('delivery-address-field');
        const deliveryAddress = document.getElementById('delivery-address');
        
        fulfillmentType.addEventListener('change', function() {
            if (this.value === 'delivery') {
                deliveryAddressField.style.display = 'block';
                deliveryAddress.required = true;
            } else {
                deliveryAddressField.style.display = 'none';
                deliveryAddress.required = false;
            }
        });
        
        if (needsPrescription) {
            const uploadBtn = document.getElementById('upload-prescription-btn');
            const fileInput = document.getElementById('prescription-file');
            const uploadStatus = document.getElementById('prescription-upload-status');
            
            uploadBtn.addEventListener('click', async function() {
                const file = fileInput.files[0];
                
                if (!file) {
                    uploadStatus.innerHTML = '<span style="color: var(--danger);">Please select a file first.</span>';
                    return;
                }
                
                uploadBtn.disabled = true;
                uploadBtn.textContent = 'Uploading...';
                uploadStatus.innerHTML = '';
                
                const formData = new FormData();
                formData.append('file', file);
                
                try {
                    const token = CustomerApi.getToken();
                    
                    const response = await fetch('/api/v1/customer/prescriptions', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`,
                        },
                        body: formData,
                    });
                    
                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Unable to upload prescription.');
                    }
                    
                    uploadStatus.innerHTML = '<span style="color: var(--success);">✅ Prescription uploaded! The pharmacy will review it. Refresh this page after approval.</span>';
                    fileInput.value = '';
                } catch (error) {
                    uploadStatus.innerHTML = `<span style="color: var(--danger);">${error.message}</span>`;
                } finally {
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = 'Upload Prescription';
                }
            });
        }
        
        document.getElementById('checkout-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            if (needsPrescription) {
                alert('Please upload an approved prescription before checking out.');
                return;
            }
            
            const submitBtn = document.getElementById('checkout-submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            checkoutError.style.display = 'none';
            
            const formData = {
                fulfillment_type: fulfillmentType.value,
                delivery_address: fulfillmentType.value === 'delivery' ? deliveryAddress.value.trim() : null,
            };
            
            try {
                const order = await CustomerApi.post('/customer/checkout', formData);
                localStorage.setItem('pending_order_id', order.id);
                
                const payment = await CustomerApi.post(`/customer/orders/${order.id}/pay`);
                
                if (payment.authorization_url) {
                    window.location.href = payment.authorization_url;
                } else {
                    window.location.href = `/customer/orders/${order.id}`;
                }
            } catch (error) {
                if (error.status === 422 && error.data && error.data.errors) {
                    const messages = [];
                    Object.keys(error.data.errors).forEach(function(key) {
                        messages.push(...error.data.errors[key]);
                    });
                    checkoutError.textContent = messages.join(', ');
                } else {
                    checkoutError.textContent = error.message || 'Unable to place order.';
                }
                checkoutError.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Place Order & Pay';
            }
        });
    } catch (error) {
        checkoutLoading.style.display = 'none';
        checkoutError.textContent = error.message || 'Unable to load checkout.';
        checkoutError.style.display = 'block';
    }
}

loadCheckout();