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
    salesTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">No sales found</td></tr>';
    return;
  }

  sales.forEach(function(sale) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${sale.id}</td>
      <td>${sale.customer_name || 'Walk-in Customer'}</td>
      <td>${formatCurrency(sale.total)}</td>
      <td>${sale.items_count || 0}</td>
      <td>${formatDate(sale.created_at)}</td>
      <td>
        <button class="btn btn-secondary" onclick="viewSale(${sale.id})">View</button>
      </td>
    `;
    salesTableBody.appendChild(tr);
  });
}

function renderPagination() {
  paginationContainer.innerHTML = '';
  
  if (totalPages <= 1) return;
  
  const prevBtn = document.createElement('button');
  prevBtn.className = 'btn btn-secondary';
  prevBtn.textContent = 'Previous';
  prevBtn.disabled = currentPage === 1;
  prevBtn.onclick = function() { loadSales(currentPage - 1); };
  paginationContainer.appendChild(prevBtn);
  
  const pageInfo = document.createElement('span');
  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
  pageInfo.style.margin = '0 12px';
  paginationContainer.appendChild(pageInfo);
  
  const nextBtn = document.createElement('button');
  nextBtn.className = 'btn btn-secondary';
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
    salesError.style.display = 'block';
  }
}

window.viewSale = async function(id) {
  try {
    const sale = await Api.get(`/staff/sales/${id}`);
    const items = sale.items || [];
    
    let itemsHtml = '';
    items.forEach(function(item) {
      itemsHtml += `
        <tr>
          <td>${item.product ? item.product.name : 'Product'}</td>
          <td>${item.quantity}</td>
          <td>${formatCurrency(item.unit_price)}</td>
          <td>${formatCurrency(item.line_total || item.unit_price * item.quantity)}</td>
        </tr>
      `;
    });
    
    saleDetails.innerHTML = `
      <div style="margin-bottom: 20px;">
        <p><strong>Receipt #:</strong> ${sale.id}</p>
        <p><strong>Date:</strong> ${formatDate(sale.created_at)}</p>
        <p><strong>Customer:</strong> ${sale.customer_name || 'Walk-in Customer'}</p>
        <p><strong>Cashier:</strong> ${sale.cashier || 'N/A'}</p>
        <p><strong>Payment Method:</strong> ${sale.payment_method}</p>
      </div>
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          ${itemsHtml || '<tr><td colspan="4" class="empty-state">No items</td></tr>'}
        </tbody>
      </table>
      <div style="margin-top: 20px; text-align: right;">
        <p><strong>Subtotal:</strong> ${formatCurrency(sale.subtotal)}</p>
        <p><strong>Discount:</strong> ${formatCurrency(sale.discount_total)}</p>
        <p style="font-size: 18px;"><strong>Total:</strong> ${formatCurrency(sale.total)}</p>
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