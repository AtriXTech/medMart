const prescriptionError = document.getElementById('prescription-error');
const prescriptionLoading = document.getElementById('prescription-loading');
const prescriptionContent = document.getElementById('prescription-content');
const prescriptionInfo = document.getElementById('prescription-info');
const reviewModal = document.getElementById('review-modal');
const reviewForm = document.getElementById('review-form');
const reviewStatus = document.getElementById('review-status');
const reviewNotes = document.getElementById('review-notes');
const reviewError = document.getElementById('review-error');
const reviewSubmitBtn = document.getElementById('review-submit-btn');
const closeReviewBtn = document.getElementById('close-review-btn');
const cancelReviewBtn = document.getElementById('cancel-review-btn');
const approveBtn = document.getElementById('approve-btn');
const rejectBtn = document.getElementById('reject-btn');

const prescriptionId = new URLSearchParams(window.location.search).get('id');

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

function renderPrescriptionInfo(prescription) {
  const customer = prescription.customer || {};
  
  prescriptionInfo.innerHTML = `
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      <div>
        <strong>Prescription ID:</strong> ${prescription.id}
      </div>
      <div>
        <strong>Status:</strong> ${badgeForStatus(prescription.status)}
      </div>
      <div>
        <strong>Customer:</strong> ${customer.name || 'N/A'}
      </div>
      <div>
        <strong>Email:</strong> ${customer.email || 'N/A'}
      </div>
      <div>
        <strong>Uploaded:</strong> ${formatDate(prescription.created_at)}
      </div>
      ${prescription.reviewed_at ? `
        <div>
          <strong>Reviewed:</strong> ${formatDate(prescription.reviewed_at)}
        </div>
      ` : ''}
      ${prescription.review_notes ? `
        <div>
          <strong>Review Notes:</strong> ${prescription.review_notes}
        </div>
      ` : ''}
    </div>
  `;
  
  if (prescription.status === 'pending') {
    approveBtn.style.display = 'inline-flex';
    rejectBtn.style.display = 'inline-flex';
  } else {
    approveBtn.style.display = 'none';
    rejectBtn.style.display = 'none';
  }
}

async function loadPrescription() {
    if (!Auth.requireAuth()) return;
    if (!prescriptionId) {
        window.location.href = '/staff/prescriptions';
        return;
    }
    
    prescriptionLoading.style.display = 'block';
    prescriptionContent.style.display = 'none';
    prescriptionError.style.display = 'none';
    
    try {
        const prescription = await Api.get(`/staff/prescriptions/${prescriptionId}`);
        
        renderPrescriptionInfo(prescription);
        
        const fileContainer = document.createElement('div');
        fileContainer.style.marginTop = '20px';
        fileContainer.innerHTML = `
            <p class="section-title">Prescription File</p>
            <button class="btn btn-secondary" id="download-file-btn">Download File (${prescription.original_filename || 'Prescription'})</button>
        `;
        prescriptionInfo.parentNode.insertBefore(fileContainer, prescriptionInfo.nextSibling);
        
        document.getElementById('download-file-btn').addEventListener('click', async function() {
            try {
                const token = Api.getToken();
                
                const response = await fetch(`/api/v1/staff/prescriptions/${prescriptionId}/file`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });
                
                if (!response.ok) {
                    throw new Error('Unable to download file.');
                }
                
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = prescription.original_filename || 'prescription-file';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } catch (error) {
                alert(error.message || 'Unable to download file.');
            }
        });
        
        prescriptionLoading.style.display = 'none';
        prescriptionContent.style.display = 'block';
    } catch (error) {
        prescriptionLoading.style.display = 'none';
        prescriptionError.textContent = error.message || 'Unable to load prescription.';
        prescriptionError.style.display = 'block';
    }
}

function openReviewModal(status) {
  reviewStatus.value = status;
  reviewNotes.value = '';
  reviewError.style.display = 'none';
  reviewModal.style.display = 'flex';
}

approveBtn.addEventListener('click', function() {
  openReviewModal('approved');
});

rejectBtn.addEventListener('click', function() {
  openReviewModal('rejected');
});

closeReviewBtn.addEventListener('click', function() {
  reviewModal.style.display = 'none';
});

cancelReviewBtn.addEventListener('click', function() {
  reviewModal.style.display = 'none';
});

reviewModal.addEventListener('click', function(event) {
  if (event.target === reviewModal) {
    reviewModal.style.display = 'none';
  }
});

reviewForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  reviewSubmitBtn.disabled = true;
  reviewError.style.display = 'none';
  
  try {
    await Api.patch(`/staff/prescriptions/${prescriptionId}/review`, {
      status: reviewStatus.value,
      notes: reviewNotes.value.trim() || undefined
    });
    
    reviewModal.style.display = 'none';
    loadPrescription();
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      const messages = [];
      Object.keys(error.data.errors).forEach(function(key) {
        messages.push(...error.data.errors[key]);
      });
      reviewError.textContent = messages.join(', ');
    } else {
      reviewError.textContent = error.message || 'Unable to review prescription.';
    }
    reviewError.style.display = 'block';
  } finally {
    reviewSubmitBtn.disabled = false;
  }
});

loadPrescription();