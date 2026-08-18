const prescriptionsError = document.getElementById('prescriptions-error');
const prescriptionsLoading = document.getElementById('prescriptions-loading');
const prescriptionsContent = document.getElementById('prescriptions-content');

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
    if (!prescriptions || prescriptions.length === 0) {
        prescriptionsContent.innerHTML = `
            <div class="card">
                <div class="empty-state">No prescriptions uploaded yet</div>
                <a href="/customer/prescriptions/upload" class="btn btn-primary btn-block" style="margin-top: 16px;">Upload Prescription</a>
            </div>
        `;
        return;
    }
    
    let html = '';
    prescriptions.forEach(function(prescription) {
        html += `
            <div class="card" style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong>${prescription.original_filename || 'Prescription'}</strong>
                    ${badgeForStatus(prescription.status)}
                </div>
                <div style="font-size: 13px; color: var(--text-muted);">
                    <div>Uploaded: ${formatDate(prescription.created_at)}</div>
                    ${prescription.reviewed_at ? `<div>Reviewed: ${formatDate(prescription.reviewed_at)}</div>` : ''}
                    ${prescription.rejection_reason ? `<div style="color: var(--danger);">Reason: ${prescription.rejection_reason}</div>` : ''}
                </div>
            </div>
        `;
    });
    
    prescriptionsContent.innerHTML = html;
}

async function loadPrescriptions() {
    if (!CustomerAuth.requireAuth()) return;
    
    prescriptionsLoading.style.display = 'block';
    prescriptionsContent.style.display = 'none';
    prescriptionsError.style.display = 'none';
    
    try {
        const data = await CustomerApi.get('/customer/prescriptions?per_page=50');
        renderPrescriptions(data.data || data);
        prescriptionsLoading.style.display = 'none';
        prescriptionsContent.style.display = 'block';
    } catch (error) {
        prescriptionsLoading.style.display = 'none';
        prescriptionsError.textContent = error.message || 'Unable to load prescriptions.';
        prescriptionsError.style.display = 'block';
    }
}

loadPrescriptions();