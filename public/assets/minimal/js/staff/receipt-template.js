/*
  Shared invoice/receipt renderer.
  Included by BOTH pos.blade.php and order-details.blade.php (via a
  <script> tag loaded before pos.js / order-details.js) so the invoice
  layout is defined in exactly one place instead of duplicated and
  drifting apart over time.

  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadPharmacy(), getCashierName(), formatCurrency(),
    formatDate(), humanize(), the items table, the footer, the overall
    layout order.
  - NEW: render() now accepts two new optional params —
    * customerName — rendered as a "Customer" row in the info block,
      right after Invoice ID. Omitted entirely if not provided, so
      order-details.js (which doesn't pass this) keeps rendering exactly
      as before with no visual change.
    * discountTotal — rendered as a "Discount" row between Subtotal and
      Total, but ONLY when it's greater than 0. Also omitted entirely
      otherwise, so existing callers/receipts with no discount are
      unaffected.

  Usage:
    const pharmacy = await MedMartReceipt.loadPharmacy();       // cached after first call
    const cashierName = MedMartReceipt.getCashierName();
    const html = MedMartReceipt.render({
      pharmacy,
      invoiceId: sale.id,
      cashierName,
      customerName: sale.customer_name,     // optional — new
      date: sale.created_at,
      items: [{ name, qty, rate, amount }, ...],
      subtotal: sale.subtotal,
      discountTotal: sale.discount_total,   // optional — new
      total: sale.total,
      paymentMethod: sale.payment_method,   // optional — omitted from the
                                             // Total line if not provided
      status: sale.status,                  // optional
    });
    receiptContentEl.innerHTML = html;
*/

window.MedMartReceipt = (function () {
  let cachedPharmacy = null;

  function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
  }

  function formatDate(dateString) {
    if (!dateString) return new Date().toLocaleString();
    const date = new Date(dateString);
    return isNaN(date) ? dateString : date.toLocaleString();
  }

  function humanize(str) {
    return String(str || '').replace(/_/g, ' ');
  }

  // Fetches /staff/pharmacy-settings once and caches the result for the
  // rest of the page's life. Falls back to a bare "MedMart Pharmacy"
  // name if the request fails, so a receipt can still print.
  async function loadPharmacy() {
    if (cachedPharmacy) return cachedPharmacy;
    try {
      const data = await Api.get('/staff/pharmacy-settings');
      cachedPharmacy = {
        name: data.name || 'MedMart Pharmacy',
        address: data.address || '',
        phone: data.phone || '',
      };
    } catch (error) {
      console.error('Unable to load pharmacy settings for receipt:', error);
      cachedPharmacy = { name: 'MedMart Pharmacy', address: '', phone: '' };
    }
    return cachedPharmacy;
  }

  // The logged-in staff member printing/generating this receipt.
  function getCashierName() {
    const user = Api.getUser();
    return (user && (user.name || user.email)) || 'N/A';
  }

  function render({ pharmacy, invoiceId, cashierName, customerName, date, items, subtotal, discountTotal, total, paymentMethod, status }) {
    const ph = pharmacy || { name: 'MedMart Pharmacy', address: '', phone: '' };

    const itemsHtml = (items || []).map(function (item, index) {
      return `<tr class="border-b border-[#EAF1FB] last:border-0">
        <td class="py-2 font-inter text-[13px] text-[#171E26]">${index + 1}</td>
        <td class="py-2 font-inter text-[13px] text-[#171E26]">${item.name}</td>
        <td class="py-2 font-inter text-[13px] text-[#171E26] text-center">${item.qty}</td>
        <td class="py-2 font-inter text-[13px] text-[#171E26] text-right">${formatCurrency(item.rate)}</td>
        <td class="py-2 font-inter text-[13px] font-semibold text-[#171E26] text-right">${formatCurrency(item.amount)}</td>
      </tr>`;
    }).join('');

    const totalLabel = paymentMethod ? `Total (${humanize(paymentMethod)})` : 'Total';
    const discountValue = Number(discountTotal || 0);

    return `
      <div class="text-center mb-4">
        <h3 class="font-manrope font-extrabold text-[18px] text-[#171E26]">${ph.name}</h3>
        ${ph.address ? `<p class="font-inter text-[12px] text-[#171E26]/60 mt-1">${ph.address}</p>` : ''}
        ${ph.phone ? `<p class="font-inter text-[12px] text-[#171E26]/60">Call us on: ${ph.phone}</p>` : ''}
      </div>

      <div class="space-y-1 mb-4">
        <div class="flex justify-between font-inter text-[13px] text-[#171E26]/70">
          <span>Invoice ID</span><strong class="text-[#171E26]">#${invoiceId}</strong>
        </div>
        ${customerName ? `<div class="flex justify-between font-inter text-[13px] text-[#171E26]/70">
          <span>Customer</span><strong class="text-[#171E26]">${customerName}</strong>
        </div>` : ''}
        <div class="flex justify-between font-inter text-[13px] text-[#171E26]/70">
          <span>Cashier</span><strong class="text-[#171E26]">${cashierName}</strong>
        </div>
        <div class="flex justify-between font-inter text-[13px] text-[#171E26]/70">
          <span>Date</span><strong class="text-[#171E26]">${formatDate(date)}</strong>
        </div>
      </div>

      <hr class="border-t border-dashed border-[#EAF1FB] my-4">

      <table class="w-full">
        <thead>
          <tr class="border-b border-[#EAF1FB]">
            <th class="py-2 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">S/N</th>
            <th class="py-2 text-left font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Item</th>
            <th class="py-2 text-center font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Qty</th>
            <th class="py-2 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Rate</th>
            <th class="py-2 text-right font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Amount</th>
          </tr>
        </thead>
        <tbody>${itemsHtml}</tbody>
      </table>

      <div class="mt-4 space-y-1.5 text-right">
        <p class="font-inter text-[13px] text-[#171E26]/70">Subtotal: <strong class="text-[#171E26]">${formatCurrency(subtotal)}</strong></p>
        ${discountValue > 0 ? `<p class="font-inter text-[13px] text-[#171E26]/70">Discount: <strong class="text-[#171E26]">-${formatCurrency(discountValue)}</strong></p>` : ''}
        <p class="font-manrope text-lg font-extrabold text-[#2775E4] capitalize">${totalLabel}: ${formatCurrency(total)}</p>
        ${status ? `<p class="font-inter text-[13px] text-[#171E26]/70">Status: <strong class="text-[#171E26] capitalize">${humanize(status)}</strong></p>` : ''}
      </div>

      <hr class="border-t border-dashed border-[#EAF1FB] my-4">

      <div class="text-center space-y-1">
        <p class="font-inter text-[12px] text-[#171E26]/60">Thanks for your patronage. Call again!</p>
        <p class="font-inter text-[11px] text-[#171E26]/45">Goods bought in good condition are not returnable.</p>
        <p class="font-inter text-[11px] font-semibold text-[#171E26]/50 mt-2">Powered by MedMart.com</p>
      </div>
    `;
  }

  return { loadPharmacy, getCashierName, render };
})();