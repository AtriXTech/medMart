/*
  Out of Stock Products page.

  There is no dedicated "out of stock" or "stock_status" filter on
  /staff/products in the API — only per_page, category_id are documented,
  and search/availability work in practice but availability != stock level.
  So this page fetches products (walking all pages if there's more than
  one) and filters client-side for stock_quantity <= 0, using the same
  priority logic as the stockBadge() fix on the Products/Product Details
  pages: zero stock always means "out of stock" regardless of is_available.

  "Leaves by itself when restocked": handled via auto-refresh polling
  (every 30s). There's no websocket/push mechanism available, so this is
  the practical equivalent — once a product's stock_quantity rises above
  0, the next automatic refresh simply won't include it anymore.

  For a large catalog, walking every page on every refresh could get slow.
  If that becomes a problem, the real fix is asking the backend for a
  proper ?stock_status=out_of_stock query param so filtering happens
  server-side instead of here.
*/

const outOfStockError = document.getElementById('out-of-stock-error');
const outOfStockLoading = document.getElementById('out-of-stock-loading');
const outOfStockContent = document.getElementById('out-of-stock-content');
const outOfStockTableBody = document.getElementById('out-of-stock-table-body');
const refreshBtn = document.getElementById('refresh-btn');
const lastCheckedEl = document.getElementById('last-checked');

const AUTO_REFRESH_INTERVAL_MS = 30000;
let isFirstLoad = true;
let refreshTimer = null;

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return '₦' + value.toLocaleString();
}

function formatTime(date) {
    return date.toLocaleTimeString('en-NG', { hour: 'numeric', minute: '2-digit' });
}

// Fetches every page of /staff/products and returns the full merged list.
// Needed because there's no server-side stock filter to rely on.
async function fetchAllProducts() {
    const perPage = 100;
    let page = 1;
    let lastPage = 1;
    let allProducts = [];

    do {
        const response = await Api.get(`/staff/products?per_page=${perPage}&page=${page}`);
        const products = response.data || [];
        allProducts = allProducts.concat(products);
        lastPage = (response.meta && response.meta.last_page) || 1;
        page += 1;
    } while (page <= lastPage);

    return allProducts;
}

function renderOutOfStock(products) {
    const outOfStock = products.filter(function (product) {
        return Number(product.stock_quantity) <= 0;
    });

    if (outOfStock.length === 0) {
        outOfStockTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="py-14 text-center">
                    <i class="ph ph-check-circle text-3xl text-[#08AEBC]"></i>
                    <p class="font-inter text-sm text-[#171E26]/50 mt-2">Nothing is out of stock right now.</p>
                </td>
            </tr>
        `;
        return;
    }

    outOfStockTableBody.innerHTML = outOfStock.map(function (product) {
        const imageHtml = product.image_url
            ? `<img src="${product.image_url}" alt="${product.name}" class="w-9 h-9 object-cover rounded-lg flex-shrink-0">`
            : `<div class="w-9 h-9 rounded-lg bg-[#F7FAFD] flex items-center justify-center flex-shrink-0"><i class="ph ph-package text-[#171E26]/25 text-base"></i></div>`;

        return `
            <tr class="border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition">
                <td class="py-3 px-3">
                    <div class="flex items-center gap-3">
                        ${imageHtml}
                        <strong class="font-inter text-[14px] font-semibold text-[#171E26]">${product.name}</strong>
                    </div>
                </td>
                <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${product.category ? product.category.name : 'N/A'}</td>
                <td class="py-3 px-3 font-inter text-[13px] text-[#171E26]/60">${product.barcode || 'N/A'}</td>
                <td class="py-3 px-3 font-inter text-[14px] font-semibold text-[#171E26]">${formatCurrency(product.price)}</td>
                <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${product.reorder_level || 0}</td>
                <td class="py-3 px-3">
                    <a href="/staff/product-details?id=${product.id}"
                       class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition inline-block">
                        Restock
                    </a>
                </td>
            </tr>
        `;
    }).join('');
}

async function loadOutOfStock() {
    if (!Auth.requireAuth()) return;

    // Only show the full-page spinner on the very first load. Auto-refreshes
    // and manual refreshes update the table quietly so the page doesn't
    // flicker every 30 seconds.
    if (isFirstLoad) {
        outOfStockLoading.style.display = 'block';
        outOfStockContent.style.display = 'none';
        outOfStockError.style.display = 'none';
    }

    try {
        const products = await fetchAllProducts();
        renderOutOfStock(products);

        lastCheckedEl.textContent = `Last checked: ${formatTime(new Date())}`;

        if (isFirstLoad) {
            outOfStockLoading.style.display = 'none';
            outOfStockContent.style.display = 'block';
            isFirstLoad = false;
        }
    } catch (error) {
        console.error('Unable to load out of stock products:', error);
        if (isFirstLoad) {
            outOfStockLoading.style.display = 'none';
            outOfStockError.textContent = error.message || 'Unable to load products.';
            outOfStockError.style.display = 'flex';
        }
        // Silent failure on background auto-refreshes — don't interrupt the
        // page with an error banner every 30s if one refresh fails; the
        // list simply keeps showing the last successful result.
    }
}

refreshBtn.addEventListener('click', function () {
    loadOutOfStock();
});

loadOutOfStock();
refreshTimer = setInterval(loadOutOfStock, AUTO_REFRESH_INTERVAL_MS);