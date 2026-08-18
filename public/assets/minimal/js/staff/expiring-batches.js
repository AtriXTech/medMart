const batchesError = document.getElementById('batches-error');
const batchesLoading = document.getElementById('batches-loading');
const batchesContent = document.getElementById('batches-content');
const batchesTableBody = document.getElementById('batches-table-body');

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function daysUntil(expiryDate) {
    const now = new Date();
    const expiry = new Date(expiryDate);
    const diff = Math.ceil((expiry - now) / (1000 * 60 * 60 * 24));
    return diff;
}

function badgeForDays(days) {
    if (days <= 30) {
        return '<span class="badge badge-danger">' + days + ' days left</span>';
    } else if (days <= 60) {
        return '<span class="badge badge-warning">' + days + ' days left</span>';
    } else {
        return '<span class="badge badge-muted">' + days + ' days left</span>';
    }
}

function renderBatches(batches) {
    batchesTableBody.innerHTML = '';
    
    if (!batches || batches.length === 0) {
        batchesTableBody.innerHTML = '<tr><td colspan="5" class="empty-state">No expiring batches found</td></tr>';
        return;
    }
    
    batches.forEach(function(batch) {
        const days = daysUntil(batch.expiry_date);
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${batch.product ? batch.product.name : 'N/A'}</td>
            <td>${batch.batch_number}</td>
            <td>${batch.quantity}</td>
            <td>${formatDate(batch.expiry_date)}</td>
            <td>${badgeForDays(days)}</td>
        `;
        batchesTableBody.appendChild(tr);
    });
}

async function loadExpiringBatches() {
    if (!Auth.requireAuth()) return;
    
    batchesLoading.style.display = 'block';
    batchesContent.style.display = 'none';
    batchesError.style.display = 'none';
    
    try {
        const data = await Api.get('/staff/batches/expiring-soon?per_page=50');
        renderBatches(data.data || []);
        batchesLoading.style.display = 'none';
        batchesContent.style.display = 'block';
    } catch (error) {
        batchesLoading.style.display = 'none';
        batchesError.textContent = error.message || 'Unable to load expiring batches.';
        batchesError.style.display = 'block';
    }
}

loadExpiringBatches();