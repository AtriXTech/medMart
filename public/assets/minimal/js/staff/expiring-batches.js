/*
  CHANGE SUMMARY (vs. previous version):
  - FIXED BUG: renderBatches() now filters out any batch whose product
    has been deleted (confirmed: a deleted product's batch.product comes
    back null/missing from the API — this is what was already producing
    the "N/A" product name you were seeing). Those rows are now excluded
    entirely rather than rendered, on both the "Expiring soon" and
    "Already expired" filters, since a batch with no product should
    never be actionable regardless of which view you're in.
  - Note: this is a client-side safety net. The real fix belongs on
    /staff/batches/expiring-soon itself — it should exclude batches with
    no existing product at the query level so this data never reaches
    the frontend at all. Flagging this in case it's worth a backend fix
    too, not just this patch.
  - UNCHANGED: everything else — the days/status filter query params,
    updateDaysFilterVisibility(), badgeForDays()/daysUntil() including
    the already-expired negative-day handling, loading/error toggling,
    every element ID.
*/

const batchesError = document.getElementById('batches-error');
const batchesLoading = document.getElementById('batches-loading');
const batchesContent = document.getElementById('batches-content');
const batchesTableBody = document.getElementById('batches-table-body');

const statusFilter = document.getElementById('status-filter');
const daysFilter = document.getElementById('days-filter');
const daysFilterWrapper = document.getElementById('days-filter-wrapper');

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
    let bg, text, label;

    if (days < 0) {
        bg = '#FDEDEC'; text = '#9C3A32';
        const daysAgo = Math.abs(days);
        label = `Expired ${daysAgo} day${daysAgo === 1 ? '' : 's'} ago`;
    } else if (days <= 30) {
        bg = '#FDEDEC'; text = '#9C3A32';
        label = `${days} day${days === 1 ? '' : 's'} left`;
    } else if (days <= 60) {
        bg = '#FFF8EC'; text = '#8A6116';
        label = `${days} day${days === 1 ? '' : 's'} left`;
    } else {
        bg = '#F1F3F6'; text = '#4B5563';
        label = `${days} day${days === 1 ? '' : 's'} left`;
    }

    return `<span class="inline-block font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full" style="background:${bg};color:${text}">${label}</span>`;
}

function renderBatches(batches) {
    // FIX: drop any batch whose product has been deleted (batch.product
    // is null/missing for these) — they were showing up as "N/A" rows
    // and shouldn't be actionable at all, in either filter mode.
    const activeBatches = (batches || []).filter(function (batch) {
        return !!batch.product;
    });

    if (activeBatches.length === 0) {
        batchesTableBody.innerHTML = `<tr><td colspan="5" class="text-center py-14">
            <i class="ph-light ph-hourglass-medium text-3xl text-[#171E26]/20 block mb-2"></i>
            <p class="font-inter text-[13px] text-[#171E26]/45">No batches found</p>
        </td></tr>`;
        return;
    }

    batchesTableBody.innerHTML = activeBatches.map(function (batch) {
        const days = daysUntil(batch.expiry_date);
        return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
            <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${batch.product.name}</td>
            <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${batch.batch_number}</td>
            <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${batch.quantity}</td>
            <td class="py-3 pr-4 font-inter text-[13px] text-[#171E26]/70">${formatDate(batch.expiry_date)}</td>
            <td class="py-3">${badgeForDays(days)}</td>
        </tr>`;
    }).join('');
}

function updateDaysFilterVisibility() {
    if (statusFilter.value === 'expired') {
        daysFilterWrapper.style.display = 'none';
    } else {
        daysFilterWrapper.style.display = 'block';
    }
}

async function loadExpiringBatches() {
    if (!Auth.requireAuth()) return;

    batchesLoading.style.display = 'block';
    batchesContent.style.display = 'none';
    batchesError.style.display = 'none';

    const status = statusFilter.value;
    const days = daysFilter.value;

    let url = `/staff/batches/expiring-soon?per_page=50&status=${encodeURIComponent(status)}`;
    if (status !== 'expired') {
        url += `&days=${encodeURIComponent(days)}`;
    }

    try {
        const data = await Api.get(url);
        renderBatches(data.data || []);
        batchesLoading.style.display = 'none';
        batchesContent.style.display = 'block';
    } catch (error) {
        batchesLoading.style.display = 'none';
        batchesError.textContent = error.message || 'Unable to load expiring batches.';
        batchesError.style.display = 'block';
    }
}

statusFilter.addEventListener('change', function () {
    updateDaysFilterVisibility();
    loadExpiringBatches();
});

daysFilter.addEventListener('change', function () {
    loadExpiringBatches();
});

updateDaysFilterVisibility();
loadExpiringBatches();