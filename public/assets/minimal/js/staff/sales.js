const salesError = document.getElementById('sales-error');
const salesLoading = document.getElementById('sales-loading');
const salesContent = document.getElementById('sales-content');
const salesTableBody = document.getElementById('sales-table-body');
const dateFilter = document.getElementById('date-filter');
const paginationContainer = document.getElementById('pagination-container');
const saleModal = document.getElementById('sale-modal');
const saleDetails = document.getElementById('sale-details');
const closeSaleModalBtn = document.getElementById('close-sale-modal-btn');

let currentPage = 1;
let totalPages = 1;

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleString();
}

function renderSales(sales) {
  salesTableBody.innerHTML = '';

  if (!sales || sales.length === 0) {
    salesTableBody.innerHTML = `
      <tr>
        <td colspan="6" class="py-14 text-center">
          <i class="ph ph-receipt text-3xl text-[#171E26]/20"></i>
          <p class="font-inter text-sm text-[#171E26]/45 mt-2">No sales found</p>
        </td>
      </tr>
    `;
    return;
  }

  sales.forEach(function(sale) {
    const tr = document.createElement('tr');
    tr.className = 'border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition';
    tr.innerHTML = `
      <td class="py-3 px-3 font-inter text-[14px] font-medium text-[#171E26]">#${sale.id}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]">${sale.customer_name || 'Walk-in Customer'}</td>
      <td class="py-3 px-3 font-inter text-[14px] font-semibold text-[#171E26]">${formatCurrency(sale.total)}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${sale.items_count || 0}</td>
      <td class="py-3 px-3 font-inter text-[13px] text-[#171E26]/60 whitespace-nowrap">${formatDate(sale.created_at)}</td>
      <td class="py-3 px-3">
        <button type="button" onclick="viewSale(${sale.id})"
                class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition">
          View
        </button>
      </td>
    `;
    salesTableBody.appendChild(tr);
  });
}

function renderPagination() {
  paginationContainer.innerHTML = '';

  if (totalPages <= 1) return;

  const prevBtn = document.createElement('button');
  prevBtn.className = 'rounded-lg border border-[#DBEBFB] px-4 py-2 font-inter text-sm font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed transition';
  prevBtn.textContent = 'Previous';
  prevBtn.disabled = currentPage === 1;
  prevBtn.onclick = function() { loadSales(currentPage - 1); };
  paginationContainer.appendChild(prevBtn);

  const pageInfo = document.createElement('span');
  pageInfo.className = 'mx-3 font-inter text-sm text-[#171E26]/60';
  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
  paginationContainer.appendChild(pageInfo);

  const nextBtn = document.createElement('button');
  nextBtn.className = 'rounded-lg border border-[#DBEBFB] px-4 py-2 font-inter text-sm font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed transition';
  nextBtn.textContent = 'Next';
  nextBtn.disabled = currentPage === totalPages;
  nextBtn.onclick = function() { loadSales(currentPage + 1); };
  paginationContainer.appendChild(nextBtn);
}

async function loadSales(page = 1) {
  if (!Auth.requireAuth()) return;

  currentPage = page;
  salesLoading.style.display = 'block';
  salesContent.style.display = 'none';
  salesError.style.display = 'none';

  const params = new URLSearchParams();
  params.append('page', currentPage);
  params.append('per_page', 20);

  if (dateFilter.value) {
    params.append('date', dateFilter.value);
  }

  try {
    const data = await Api.get(`/staff/sales?${params.toString()}`);
    renderSales(data.data);
    totalPages = data.meta ? data.meta.last_page : 1;
    renderPagination();
    salesLoading.style.display = 'none';
    salesContent.style.display = 'block';
  } catch (error) {
    salesLoading.style.display = 'none';
    salesError.textContent = error.message || 'Unable to load sales.';
    salesError.style.display = 'flex';
  }
}

window.viewSale = async function(id) {
  try {
    const sale = await Api.get(`/staff/sales/${id}`);
    const items = sale.items || [];

    let itemsHtml = '';
    items.forEach(function(item) {
      itemsHtml += `
        <tr class="border-b border-[#EAF1FB] last:border-0">
          <td class="py-2 font-inter text-[13px] text-[#171E26]">${item.product ? item.product.name : 'Product'}</td>
          <td class="py-2 font-inter text-[13px] text-[#171E26] text-center">${item.quantity}</td>
          <td class="py-2 font-inter text-[13px] text-[#171E26] text-right">${formatCurrency(item.unit_price)}</td>
          <td class="py-2 font-inter text-[13px] font-semibold text-[#171E26] text-right">${formatCurrency(item.line_total || item.unit_price * item.quantity)}</td>
        </tr>
      `;
    });

    saleDetails.innerHTML = `
      <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 mb-5 pb-5 border-b border-[#EAF1FB]">
        <p class="font-inter text-[13px] text-[#171E26]/60">Receipt #: <strong class="text-[#171E26]">${sale.id}</strong></p>
        <p class="font-inter text-[13px] text-[#171E26]/60">Date: <strong class="text-[#171E26]">${formatDate(sale.created_at)}</strong></p>
        <p class="font-inter text-[13px] text-[#171E26]/60">Customer: <strong class="text-[#171E26]">${sale.customer_name || 'Walk-in Customer'}</strong></p>
        <p class="font-inter text-[13px] text-[#171E26]/60">Cashier: <strong class="text-[#171E26]">${sale.cashier || 'N/A'}</strong></p>
        <p class="font-inter text-[13px] text-[#171E26]/60">Payment Method: <strong class="text-[#171E26] capitalize">${sale.payment_method}</strong></p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[420px]">
          <thead>
            <tr class="border-b border-[#EAF1FB]">
              <th class="text-left py-2 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Item</th>
              <th class="text-center py-2 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Qty</th>
              <th class="text-right py-2 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Price</th>
              <th class="text-right py-2 font-inter text-[11px] font-semibold uppercase tracking-wide text-[#171E26]/40">Total</th>
            </tr>
          </thead>
          <tbody>
            ${itemsHtml || '<tr><td colspan="4" class="py-8 text-center font-inter text-sm text-[#171E26]/45">No items</td></tr>'}
          </tbody>
        </table>
      </div>
      <div class="mt-4 pt-4 border-t border-[#EAF1FB] text-right space-y-1.5">
        <p class="font-inter text-[13px] text-[#171E26]/70">Subtotal: <strong class="text-[#171E26]">${formatCurrency(sale.subtotal)}</strong></p>
        <p class="font-inter text-[13px] text-[#171E26]/70">Discount: <strong class="text-[#171E26]">${formatCurrency(sale.discount_total)}</strong></p>
        <p class="font-manrope text-lg font-extrabold text-[#2775E4]">Total: ${formatCurrency(sale.total)}</p>
      </div>
    `;

    saleModal.style.display = 'flex';
  } catch (error) {
    alert(error.message || 'Unable to load sale details.');
  }
};

closeSaleModalBtn.addEventListener('click', function() {
  saleModal.style.display = 'none';
});

saleModal.addEventListener('click', function(event) {
  if (event.target === saleModal) {
    saleModal.style.display = 'none';
  }
});

dateFilter.addEventListener('change', function() {
  loadSales(1);
});

loadSales();