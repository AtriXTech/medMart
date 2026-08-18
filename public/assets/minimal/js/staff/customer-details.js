const customerError = document.getElementById('customer-error');
const customerLoading = document.getElementById('customer-loading');
const customerContent = document.getElementById('customer-content');
const customerInfo = document.getElementById('customer-info');
const ordersTableBody = document.getElementById('orders-table-body');

const customerLinkId = new URLSearchParams(window.location.search).get('id');

function formatCurrency(amount) {
  const value = Number(amount || 0);
  return '₦' + value.toLocaleString();
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleString();
}

function badgeForStatus(status) {
  const map = {
    pending: 'badge-warning',
    processing: 'badge-warning',
    shipped: 'badge-warning',
    delivered: 'badge-success',
    completed: 'badge-success',
    cancelled: 'badge-danger',
  };
  const cls = map[status] || 'badge-muted';
  return `<span class="badge ${cls}">${status}</span>`;
}

function renderCustomerInfo(link) {
  const customer = link.customer || {};
  
  customerInfo.innerHTML = `
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      <div>
        <strong>Name:</strong> ${customer.name || 'N/A'}
      </div>
      <div>
        <strong>Email:</strong> ${customer.email || 'N/A'}
      </div>
      <div>
        <strong>Username:</strong> ${customer.username || 'N/A'}
      </div>
      <div>
        <strong>Email Verified:</strong> ${customer.email_verified ? 'Yes' : 'No'}
      </div>
      <div>
        <strong>Status:</strong> 
        <span class="badge ${link.is_suspended ? 'badge-danger' : 'badge-success'}">
          ${link.is_suspended ? 'Suspended' : 'Active'}
        </span>
      </div>
      <div>
        <strong>Linked Since:</strong> ${formatDate(link.linked_at)}
      </div>
    </div>
    <div style="margin-top: 16px;">
      ${link.is_suspended 
        ? `<button class="btn btn-success" onclick="unsuspendCustomer()">Unsuspend Customer</button>`
        : `<button class="btn btn-danger" onclick="suspendCustomer()">Suspend Customer</button>`
      }
    </div>
  `;
}

function renderOrders(orders) {
  ordersTableBody.innerHTML = '';
  
  if (!orders || orders.length === 0) {
    ordersTableBody.innerHTML = '<tr><td colspan="5" class="empty-state">No orders found</td></tr>';
    return;
  }
  
  orders.forEach(function(order) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${order.id}</td>
      <td>${badgeForStatus(order.status)}</td>
      <td>${formatCurrency(order.total)}</td>
      <td>${formatDate(order.created_at)}</td>
      <td>
        <button class="btn btn-secondary" onclick="viewOrder(${order.id})">View</button>
      </td>
    `;
    ordersTableBody.appendChild(tr);
  });
}

async function loadCustomerDetails() {
  if (!Auth.requireAuth()) return;
  if (!customerLinkId) {
    window.location.href = '/staff/customers';
    return;
  }
  
  customerLoading.style.display = 'block';
  customerContent.style.display = 'none';
  customerError.style.display = 'none';
  
  try {
    const link = await Api.get(`/staff/customers/${customerLinkId}`);
    const ordersData = await Api.get(`/staff/customers/${customerLinkId}/orders?per_page=20`);
    
    renderCustomerInfo(link);
    renderOrders(ordersData.data || ordersData);
    
    customerLoading.style.display = 'none';
    customerContent.style.display = 'block';
  } catch (error) {
    customerLoading.style.display = 'none';
    customerError.textContent = error.message || 'Unable to load customer details.';
    customerError.style.display = 'block';
  }
}

window.suspendCustomer = async function() {
  if (!confirm('Are you sure you want to suspend this customer?')) return;
  
  try {
    await Api.patch(`/staff/customers/${customerLinkId}/suspend`);
    loadCustomerDetails();
  } catch (error) {
    alert(error.message || 'Unable to suspend customer.');
  }
};

window.unsuspendCustomer = async function() {
  if (!confirm('Are you sure you want to unsuspend this customer?')) return;
  
  try {
    await Api.patch(`/staff/customers/${customerLinkId}/unsuspend`);
    loadCustomerDetails();
  } catch (error) {
    alert(error.message || 'Unable to unsuspend customer.');
  }
};

window.viewOrder = function(id) {
  window.location.href = `/staff/order-details?id=${id}`;
};

loadCustomerDetails();