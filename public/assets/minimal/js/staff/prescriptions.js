const prescriptionsError = document.getElementById('prescriptions-error');
const prescriptionsLoading = document.getElementById('prescriptions-loading');
const prescriptionsContent = document.getElementById('prescriptions-content');
const prescriptionsTableBody = document.getElementById('prescriptions-table-body');
const statusFilter = document.getElementById('status-filter');
const paginationContainer = document.getElementById('pagination-container');

let currentPage = 1;
let totalPages = 1;

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleString();
}

function badgeForStatus(status) {
  const map = {
    pending: 'badge-warning',
    approved: 'badge-success',
    rejected: 'badge-danger',
    fulfilled: 'badge-success',
  };
  const cls = map[status] || 'badge-muted';
  return `<span class="badge ${cls}">${status}</span>`;
}

function renderPrescriptions(prescriptions) {
  prescriptionsTableBody.innerHTML = '';
  
  if (!prescriptions || prescriptions.length === 0) {
    prescriptionsTableBody.innerHTML = '<tr><td colspan="7" class="empty-state">No prescriptions found</td></tr>';
    return;
  }

  prescriptions.forEach(function(prescription) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${prescription.id}</td>
      <td>${prescription.customer ? prescription.customer.name : 'N/A'}</td>
      <td>${prescription.file_url ? '<a href="' + prescription.file_url + '" target="_blank">View File</a>' : 'N/A'}</td>
      <td>${badgeForStatus(prescription.status)}</td>
      <td>${formatDate(prescription.created_at)}</td>
      <td>
        <button class="btn btn-secondary" onclick="viewPrescription(${prescription.id})">View</button>
        ${prescription.status === 'pending' 
          ? `<button class="btn btn-success" onclick="approvePrescription(${prescription.id})">Approve</button>
             <button class="btn btn-danger" onclick="rejectPrescription(${prescription.id})">Reject</button>`
          : ''
        }
      </td>
    `;
    prescriptionsTableBody.appendChild(tr);
  });
}

function renderPagination() {
  paginationContainer.innerHTML = '';
  
  if (totalPages <= 1) return;
  
  const prevBtn = document.createElement('button');
  prevBtn.className = 'btn btn-secondary';
  prevBtn.textContent = 'Previous';
  prevBtn.disabled = currentPage === 1;
  prevBtn.onclick = function() { loadPrescriptions(currentPage - 1); };
  paginationContainer.appendChild(prevBtn);
  
  const pageInfo = document.createElement('span');
  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
  pageInfo.style.margin = '0 12px';
  paginationContainer.appendChild(pageInfo);
  
  const nextBtn = document.createElement('button');
  nextBtn.className = 'btn btn-secondary';
  nextBtn.textContent = 'Next';
  nextBtn.disabled = currentPage === totalPages;
  nextBtn.onclick = function() { loadPrescriptions(currentPage + 1); };
  paginationContainer.appendChild(nextBtn);
}

async function loadPrescriptions(page = 1) {
  if (!Auth.requireAuth()) return;
  
  currentPage = page;
  prescriptionsLoading.style.display = 'block';
  prescriptionsContent.style.display = 'none';
  prescriptionsError.style.display = 'none';
  
  const params = new URLSearchParams();
  params.append('page', currentPage);
  params.append('per_page', 20);
  
  if (statusFilter.value) {
    params.append('status', statusFilter.value);
  }
  
  try {
    const data = await Api.get(`/staff/prescriptions?${params.toString()}`);
    renderPrescriptions(data.data);
    totalPages = data.meta ? data.meta.last_page : 1;
    renderPagination();
    prescriptionsLoading.style.display = 'none';
    prescriptionsContent.style.display = 'block';
  } catch (error) {
    prescriptionsLoading.style.display = 'none';
    prescriptionsError.textContent = error.message || 'Unable to load prescriptions.';
    prescriptionsError.style.display = 'block';
  }
}

window.viewPrescription = function(id) {
  window.location.href = `/staff/prescription-details?id=${id}`;
};

window.approvePrescription = async function(id) {
  if (!confirm('Are you sure you want to approve this prescription?')) return;
  
  try {
    await Api.patch(`/staff/prescriptions/${id}/review`, { status: 'approved' });
    loadPrescriptions();
  } catch (error) {
    alert(error.message || 'Unable to approve prescription.');
  }
};

window.rejectPrescription = async function(id) {
  if (!confirm('Are you sure you want to reject this prescription?')) return;
  
  try {
    await Api.patch(`/staff/prescriptions/${id}/review`, { status: 'rejected' });
    loadPrescriptions();
  } catch (error) {
    alert(error.message || 'Unable to reject prescription.');
  }
};

statusFilter.addEventListener('change', function() {
  loadPrescriptions(1);
});

loadPrescriptions();