<x-layouts.staff title="Point of Sale" active="pos">
    <div class="alert alert-error" id="pos-error" style="display: none;"></div>

    <div id="pos-content" style="display: grid; grid-template-columns: 1fr 400px; gap: 20px;">
        <div>
            <div class="card" style="margin-bottom: 20px;">
                <div class="field" style="margin: 0;">
                    <input type="text" id="pos-product-search" placeholder="Search products by name or barcode...">
                </div>
            </div>
            
            <div id="pos-product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;"></div>
        </div>

        <div>
            <div class="card" style="position: sticky; top: 20px;">
                <p class="section-title">Current Sale</p>
                <div id="pos-cart-items" style="max-height: 400px; overflow-y: auto;"></div>
                
                <div style="margin-top: 20px;">
                    <div class="field">
                        <label for="pos-customer-name">Customer Name</label>
                        <input type="text" id="pos-customer-name" placeholder="Walk-in Customer">
                    </div>
                    <div class="field">
                        <label for="pos-payment-method">Payment Method</label>
                        <select id="pos-payment-method">
                            <option value="cash">Cash</option>
                            <option value="pos">POS</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="pos-discount">Discount Amount</label>
                        <input type="number" id="pos-discount" min="0" step="0.01" value="0">
                    </div>
                    
                    <div style="border-top: 1px solid var(--border); padding-top: 12px; margin-top: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span>Subtotal:</span>
                            <strong id="pos-subtotal">₦0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span>Discount:</span>
                            <strong id="pos-discount-display">₦0</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 18px;">
                            <span>Total:</span>
                            <strong id="pos-total">₦0</strong>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 8px;">
                        <button class="btn btn-secondary" id="pos-clear-cart-btn">Clear</button>
                        <button class="btn btn-primary" id="pos-checkout-btn" style="flex: 1;">Complete Sale</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="receipt-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title">Receipt</h3>
                <button type="button" class="close-btn" id="close-receipt-btn">&times;</button>
            </div>
            <div id="receipt-content"></div>
            <div style="margin-top: 20px; text-align: center;">
                <button class="btn btn-primary" id="new-sale-btn">New Sale</button>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/pos.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>