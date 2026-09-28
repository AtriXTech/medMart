/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: every API call and the exact sequence they happen in —
    GET /customer/cart, GET /customer/prescriptions?per_page=50 (only
    when the cart contains prescription-required items), the raw fetch()
    POST to /api/v1/customer/prescriptions with FormData + Bearer token
    (kept as a raw fetch rather than CustomerApi.post since file uploads
    need multipart form data, same as before), POST /customer/checkout,
    POST /customer/orders/:id/pay, the authorization_url redirect,
    localStorage.setItem('pending_order_id', ...).
  - UNCHANGED: the prescription-gating logic — checkout stays disabled
    and relabeled "Upload Prescription First" until an approved
    prescription exists for any Rx-required cart item. Delivery address
    required/visible only when fulfillment_type is 'delivery'. Same 422
    error handling.
  - CHANGED (presentation only): all inline style="..." strings and
    emoji (⚠️ ✅) replaced with Tailwind markup and Phosphor icons,
    matching the rest of the customer app (Cart, Products, Product Detail).
*/

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

        const itemsHtml = items.map(function (item) {
            const rxBadge = item.product.requires_prescription
                ? `<span class="inline-block font-inter text-[10px] font-semibold px-2 py-0.5 rounded-full mt-1" style="background:#FFF8EC;color:#8A6116">Rx Required</span>`
                : '';
            return `<div class="flex items-start justify-between gap-3 py-3 border-b border-[#F3F7FC] last:border-0">
                <div class="min-w-0">
                    <p class="font-inter text-[14px] font-semibold text-[#171E26]">${item.product.name}</p>
                    <p class="font-inter text-[12px] text-[#171E26]/45 mt-0.5">${item.quantity} × ${formatCurrency(item.product.price)}</p>
                    ${rxBadge}
                </div>
                <p class="font-inter text-[14px] font-semibold text-[#171E26] flex-shrink-0">${formatCurrency(item.line_total)}</p>
            </div>`;
        }).join('');

        let prescriptionSectionHtml = '';

        if (needsPrescription) {
            const prescriptionProductsList = prescriptionProducts.map(function (item) {
                return `<li class="font-inter text-[13px] text-[#171E26]/70">${item.product.name}</li>`;
            }).join('');

            prescriptionSectionHtml = `
                <div class="rounded-2xl bg-white border border-[#F5E3BF] border-l-4 p-4 md:p-5 mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ph-light ph-warning-circle text-[#8A6116] text-xl"></i>
                        <p class="font-manrope font-bold text-[15px] text-[#171E26]">Prescription Required</p>
                    </div>
                    <p class="font-inter text-[13px] text-[#171E26]/60 mb-2">The following products require an approved prescription:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-4">${prescriptionProductsList}</ul>
                    <div class="mb-3">
                        <label class="field-label">Upload Prescription</label>
                        <input type="file" id="prescription-file" accept=".jpg,.jpeg,.png,.pdf" class="field-input">
                    </div>
                    <button id="upload-prescription-btn" class="w-full py-2.5 rounded-xl border border-[#DBEBFB] font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD]">Upload Prescription</button>
                    <div id="prescription-upload-status" class="mt-2.5 font-inter text-[13px]"></div>
                </div>
            `;
        } else if (prescriptionProducts.length > 0 && hasApprovedPrescription) {
            prescriptionSectionHtml = `
                <div class="rounded-xl bg-[#E9F8EF] border border-[#CFEBDB] px-4 py-3 mb-4 flex items-center gap-2.5">
                    <i class="ph-light ph-check-circle text-[#1F7A44] text-lg flex-shrink-0"></i>
                    <p class="font-inter text-[13px] text-[#1F7A44]">Prescription approved! You can proceed with checkout.</p>
                </div>
            `;
        }

        checkoutContent.innerHTML = `
            ${prescriptionSectionHtml}

            <div class="rounded-2xl bg-white border border-[#EAF1FB] p-4 md:p-6 mb-4">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-1">Order Summary</h3>
                <div class="mt-2">${itemsHtml}</div>
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-[#EAF1FB]">
                    <span class="font-inter text-[14px] font-medium text-[#171E26]/70">Total</span>
                    <span class="font-manrope font-extrabold text-[22px] text-[#2775E4]">${formatCurrency(cart.total)}</span>
                </div>
            </div>

            <div class="rounded-2xl bg-white border border-[#EAF1FB] p-4 md:p-6">
                <h3 class="font-manrope font-bold text-[16px] text-[#171E26] mb-4">Fulfillment Method</h3>
                <form id="checkout-form" class="space-y-4">
                    <div>
                        <label for="fulfillment-type" class="field-label">Choose Method</label>
                        <select id="fulfillment-type" required class="field-input">
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    <div id="delivery-address-field" style="display: none;">
                        <label for="delivery-address" class="field-label">Delivery Address</label>
                        <textarea id="delivery-address" rows="3" class="field-input resize-none"></textarea>
                    </div>
                    <button type="submit" id="checkout-submit"
                        class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter font-semibold text-[15px] shadow-md shadow-[#2775E4]/20 disabled:opacity-50 disabled:cursor-not-allowed"
                        ${needsPrescription ? 'disabled' : ''}>
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
                    uploadStatus.innerHTML = '<span class="text-[#9C3A32]">Please select a file first.</span>';
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

                    uploadStatus.innerHTML = '<span class="text-[#1F7A44]">Prescription uploaded! The pharmacy will review it. Refresh this page after approval.</span>';
                    fileInput.value = '';
                } catch (error) {
                    uploadStatus.innerHTML = `<span class="text-[#9C3A32]">${error.message}</span>`;
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