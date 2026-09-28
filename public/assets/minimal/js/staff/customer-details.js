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
    pending: 'bg-amber-50 text-amber-600',
    processing: 'bg-amber-50 text-amber-600',
    shipped: 'bg-amber-50 text-amber-600',
    delivered: 'bg-[#DBEBFB] text-[#2775E4]',
    completed: 'bg-[#DBEBFB] text-[#2775E4]',
    cancelled: 'bg-red-50 text-red-500',
  };
  const cls = map[status] || 'bg-[#F7FAFD] text-[#171E26]/50';
  return `<span class="font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full capitalize ${cls}">${status}</span>`;
}

function renderCustomerInfo(link) {
  const customer = link.customer || {};

  console.log(customer.phone);
  console.log(customer);

  const actionButton = link.is_suspended
    ? `<button type="button" onclick="unsuspendCustomer()"
               class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#2775E4] to-[#08AEBC] text-white font-inter text-[14px] font-semibold shadow-sm hover:opacity-95 transition">
         Unsuspend Customer
       </button>`
    : `<button type="button" onclick="suspendCustomer()"
               class="px-4 py-2.5 rounded-xl border border-red-200 text-red-500 font-inter text-[14px] font-semibold hover:bg-red-50 transition">
         Suspend Customer
       </button>`;

  customerInfo.innerHTML = `
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4 mb-5">
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Name</p>
        <p class="font-inter text-[14px] font-semibold text-[#171E26]">${customer.name || 'N/A'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Email</p>
        <p class="font-inter text-[14px] text-[#171E26]">${customer.email || 'N/A'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Username</p>
        <p class="font-inter text-[14px] text-[#171E26]">${customer.username || 'N/A'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Email Verified</p>
        <p class="font-inter text-[14px] text-[#171E26]">${customer.email_verified ? 'Yes' : 'No'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Phone Number</p>
        <p class="font-inter text-[14px] text-[#171E26]">${customer.phone || 'N/A'}</p>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Status</p>
        <span class="font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full ${link.is_suspended ? 'bg-red-50 text-red-500' : 'bg-[#DBEBFB] text-[#2775E4]'}">
          ${link.is_suspended ? 'Suspended' : 'Active'}
        </span>
      </div>
      <div>
        <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#171E26]/40 mb-1">Linked Since</p>
        <p class="font-inter text-[14px] text-[#171E26]">${formatDate(link.linked_at)}</p>
      </div>
    </div>
    <div>
      ${actionButton}
    </div>
  `;
}

function renderOrders(orders) {
  ordersTableBody.innerHTML = '';

  if (!orders || orders.length === 0) {
    ordersTableBody.innerHTML = `
      <tr>
        <td colspan="5" class="py-12 text-center">
          <i class="ph ph-shopping-bag-open text-3xl text-[#171E26]/20"></i>
          <p class="font-inter text-sm text-[#171E26]/45 mt-2">No orders found</p>
        </td>
      </tr>
    `;
    return;
  }

  orders.forEach(function(order) {
    const tr = document.createElement('tr');
    tr.className = 'border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition';
    tr.innerHTML = `
      <td class="py-3 px-3 font-inter text-[14px] font-medium text-[#171E26]">#${order.id}</td>
      <td class="py-3 px-3">${badgeForStatus(order.status)}</td>
      <td class="py-3 px-3 font-inter text-[14px] font-semibold text-[#171E26]">${formatCurrency(order.total)}</td>
      <td class="py-3 px-3 font-inter text-[13px] text-[#171E26]/60 whitespace-nowrap">${formatDate(order.created_at)}</td>
      <td class="py-3 px-3">
        <button type="button" onclick="viewOrder(${order.id})"
                class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition">
          View
        </button>
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
    customerError.style.display = 'flex';
  }
}

window.suspendCustomer = async function() {
  if (!(await UIModal.confirm('Are you sure you want to suspend this customer?', { danger: true }))) return;

  try {
    await Api.patch(`/staff/customers/${customerLinkId}/suspend`);
    loadCustomerDetails();
  } catch (error) {
    await UIModal.alert(error.message || 'Unable to suspend customer.');
  }
};

window.unsuspendCustomer = async function() {
  if (!(await UIModal.confirm('Are you sure you want to unsuspend this customer?'))) return;

  try {
    await Api.patch(`/staff/customers/${customerLinkId}/unsuspend`);
    loadCustomerDetails();
  } catch (error) {
    await UIModal.alert(error.message || 'Unable to unsuspend customer.');
  }
};

window.viewOrder = function(id) {
  window.location.href = `/staff/order-details?id=${id}`;
};

loadCustomerDetails();