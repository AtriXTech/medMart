/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadExpiringBatches() (same endpoint
    GET /staff/batches/expiring-soon?per_page=50, same loading/content/error
    toggle logic), daysUntil() calculation, the day thresholds
    (<=30 danger, <=60 warning, else muted).
  - CHANGED (presentation only): renderBatches() now emits Tailwind
    table rows instead of plain <td> text; badgeForDays() now returns a
    styled pill instead of a .badge class.
*/

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
    let bg, text;
    if (days <= 30) {
        bg = '#FDEDEC'; text = '#9C3A32';
    } else if (days <= 60) {
        bg = '#FFF8EC'; text = '#8A6116';
    } else {
        bg = '#F1F3F6'; text = '#4B5563';
    }
    return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${bg};color:${text}">${days} days left</span>`;
}

function renderBatches(batches) {
    if (!batches || batches.length === 0) {
        batchesTableBody.innerHTML = `<tr><td colspan="5" class="text-center py-14">
            <i class="ph-light ph-hourglass-medium text-3xl text-[#171E26]/20 block mb-2"></i>
            <p class="font-inter text-[13px] text-[#171E26]/45">No expiring batches found</p>
        </td></tr>`;
        return;
    }

    batchesTableBody.innerHTML = batches.map(function (batch) {
        const days = daysUntil(batch.expiry_date);
        return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
            <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${batch.product ? batch.product.name : 'N/A'}</td>
            <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${batch.batch_number}</td>
            <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${batch.quantity}</td>
            <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${formatDate(batch.expiry_date)}</td>
            <td class="py-3">${badgeForDays(days)}</td>
        </tr>`;
    }).join('');
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