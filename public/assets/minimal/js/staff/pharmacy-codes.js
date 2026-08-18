const pharmacyCodesError = document.getElementById('pharmacy-codes-error');
const pharmacyCodesLoading = document.getElementById('pharmacy-codes-loading');
const pharmacyCodesContent = document.getElementById('pharmacy-codes-content');
const pharmacyCodesTableBody = document.getElementById('pharmacy-codes-table-body');
const createCodeBtn = document.getElementById('create-code-btn');
const codeModal = document.getElementById('code-modal');
const codeForm = document.getElementById('code-form');
const codeFormTitle = document.getElementById('code-form-title');
const codeInput = document.getElementById('code');
const codeExpiresAtInput = document.getElementById('code-expires-at');
const codeMaxUsesInput = document.getElementById('code-max-uses');
const codeFormError = document.getElementById('code-form-error');
const codeSubmitBtn = document.getElementById('code-submit-btn');
const closeCodeModalBtn = document.getElementById('close-code-modal-btn');
const cancelCodeModalBtn = document.getElementById('cancel-code-modal-btn');
const paginationContainer = document.getElementById('pagination-container');

let currentPage = 1;
let totalPages = 1;

function openModal() {
  codeFormTitle.textContent = 'Generate Pharmacy Code';
  codeFormError.style.display = 'none';
  codeFormError.textContent = '';
  codeInput.value = '';
  codeExpiresAtInput.value = '';
  codeMaxUsesInput.value = '';
  codeSubmitBtn.textContent = 'Generate Code';
  codeModal.style.display = 'flex';
}

function closeModal() {
  codeModal.style.display = 'none';
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString();
}

function badgeForStatus(isActive) {
  if (isActive) {
    return '<span class="badge badge-success">Active</span>';
  }
  return '<span class="badge badge-danger">Inactive</span>';
}

function renderCodes(codes) {
  pharmacyCodesTableBody.innerHTML = '';
  
  if (!codes || codes.length === 0) {
    pharmacyCodesTableBody.innerHTML = '<tr><td colspan="6" class="empty-state">No pharmacy codes found</td></tr>';
    return;
  }

  codes.forEach(function(code) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${code.code}</td>
      <td>${code.uses_count || 0} / ${code.max_uses || '∞'}</td>
      <td>${formatDate(code.expires_at)}</td>
      <td>${badgeForStatus(code.is_active)}</td>
      <td>${formatDate(code.created_at)}</td>
      <td>
        <button class="btn btn-secondary" onclick="copyCode('${code.code}')">Copy</button>
      </td>
    `;
    pharmacyCodesTableBody.appendChild(tr);
  });
}

function renderPagination() {
  paginationContainer.innerHTML = '';
  
  if (totalPages <= 1) return;
  
  const prevBtn = document.createElement('button');
  prevBtn.className = 'btn btn-secondary';
  prevBtn.textContent = 'Previous';
  prevBtn.disabled = currentPage === 1;
  prevBtn.onclick = function() { loadCodes(currentPage - 1); };
  paginationContainer.appendChild(prevBtn);
  
  const pageInfo = document.createElement('span');
  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
  pageInfo.style.margin = '0 12px';
  paginationContainer.appendChild(pageInfo);
  
  const nextBtn = document.createElement('button');
  nextBtn.className = 'btn btn-secondary';
  nextBtn.textContent = 'Next';
  nextBtn.disabled = currentPage === totalPages;
  nextBtn.onclick = function() { loadCodes(currentPage + 1); };
  paginationContainer.appendChild(nextBtn);
}

async function loadCodes(page = 1) {
  if (!Auth.requireAuth()) return;
  
  currentPage = page;
  pharmacyCodesLoading.style.display = 'block';
  pharmacyCodesContent.style.display = 'none';
  pharmacyCodesError.style.display = 'none';
  
  try {
    const data = await Api.get(`/staff/pharmacy-codes?page=${currentPage}&per_page=20`);
    renderCodes(data.data);
    totalPages = data.meta ? data.meta.last_page : 1;
    renderPagination();
    pharmacyCodesLoading.style.display = 'none';
    pharmacyCodesContent.style.display = 'block';
  } catch (error) {
    pharmacyCodesLoading.style.display = 'none';
    pharmacyCodesError.textContent = error.message || 'Unable to load pharmacy codes.';
    pharmacyCodesError.style.display = 'block';
  }
}

window.copyCode = function(code) {
  navigator.clipboard.writeText(code).then(function() {
    alert('Code copied to clipboard!');
  }).catch(function() {
    prompt('Copy this code:', code);
  });
};

createCodeBtn.addEventListener('click', openModal);
closeCodeModalBtn.addEventListener('click', closeModal);
cancelCodeModalBtn.addEventListener('click', closeModal);

codeModal.addEventListener('click', function(event) {
  if (event.target === codeModal) {
    closeModal();
  }
});

codeForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  codeSubmitBtn.disabled = true;
  codeFormError.style.display = 'none';
  
  const formData = {};
  
  if (codeInput.value.trim()) {
    formData.code = codeInput.value.trim().toUpperCase();
  }
  
  if (codeExpiresAtInput.value) {
    formData.expires_at = codeExpiresAtInput.value;
  }
  
  if (codeMaxUsesInput.value) {
    formData.max_uses = parseInt(codeMaxUsesInput.value);
  }
  
  try {
    await Api.post('/staff/pharmacy-codes', formData);
    closeModal();
    loadCodes();
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      const messages = [];
      Object.keys(error.data.errors).forEach(function(key) {
        messages.push(...error.data.errors[key]);
      });
      codeFormError.textContent = messages.join(', ');
    } else {
      codeFormError.textContent = error.message || 'Unable to generate code.';
    }
    codeFormError.style.display = 'block';
  } finally {
    codeSubmitBtn.disabled = false;
  }
});

loadCodes();