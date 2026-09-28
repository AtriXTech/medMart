const productsError = document.getElementById("products-error");
const productsLoading = document.getElementById("products-loading");
const productsContent = document.getElementById("products-content");
const productsTableBody = document.getElementById("products-table-body");
const searchInput = document.getElementById("product-search");
const categoryFilter = document.getElementById("category-filter");
const availabilityFilter = document.getElementById("availability-filter");
const paginationContainer = document.getElementById("pagination-container");
const createProductBtn = document.getElementById("create-product-btn");
const productModal = document.getElementById("product-modal");
const productForm = document.getElementById("product-form");
const productFormTitle = document.getElementById("product-form-title");
const productNameInput = document.getElementById("product-name");
const productGenericNameInput = document.getElementById("product-generic-name");
const productCategoryInput = document.getElementById("product-category");
const productPriceInput = document.getElementById("product-price");
const productReorderLevelInput = document.getElementById(
    "product-reorder-level",
);
const productDescriptionInput = document.getElementById("product-description");
const productBarcodeInput = document.getElementById("product-barcode");
const productRequiresPrescriptionInput = document.getElementById(
    "product-requires-prescription",
);
const productImageInput = document.getElementById("product-image");
const productImagePreview = document.getElementById("product-image-preview");
const productIdInput = document.getElementById("product-id");
const productFormError = document.getElementById("product-form-error");
const productSubmitBtn = document.getElementById("product-submit-btn");
const closeProductModalBtn = document.getElementById("close-product-modal-btn");
const cancelProductModalBtn = document.getElementById(
    "cancel-product-modal-btn",
);

let currentPage = 1;
let totalPages = 1;
let searchTimeout = null;

function formatCurrency(amount) {
    const value = Number(amount || 0);
    return "₦" + value.toLocaleString();
}

// Stock quantity takes priority over the is_available flag — a product with
// zero stock can't actually be sold regardless of how is_available is set,
// so "Out of Stock" overrides "Available" whenever stock_quantity <= 0.
function stockBadge(product) {
    if (Number(product.stock_quantity) <= 0) {
        return { label: "Out of Stock", classes: "bg-red-50 text-red-500" };
    }
    if (product.is_available) {
        return { label: "Available", classes: "bg-[#DBEBFB] text-[#2775E4]" };
    }
    return { label: "Unavailable", classes: "bg-red-50 text-red-500" };
}

function openModal(title, product = null) {
    productFormTitle.textContent = title;
    productFormError.style.display = "none";
    productFormError.textContent = "";
    productImagePreview.style.display = "none";

    if (product) {
        productIdInput.value = product.id;
        productNameInput.value = product.name;
        productGenericNameInput.value = product.generic_name || "";
        productCategoryInput.value = product.category
            ? product.category.id
            : "";
        productPriceInput.value = product.price;
        productReorderLevelInput.value = product.reorder_level || 0;
        productDescriptionInput.value = product.description || "";
        productBarcodeInput.value = product.barcode || "";
        productRequiresPrescriptionInput.checked =
            product.requires_prescription || false;
        productSubmitBtn.textContent = "Update Product";

        if (product.image_url) {
            productImagePreview.src = product.image_url;
            productImagePreview.style.display = "block";
        }
    } else {
        productIdInput.value = "";
        productNameInput.value = "";
        productGenericNameInput.value = "";
        productCategoryInput.value = "";
        productPriceInput.value = "";
        productReorderLevelInput.value = "0";
        productDescriptionInput.value = "";
        productBarcodeInput.value = "";
        productRequiresPrescriptionInput.checked = false;
        productImageInput.value = "";
        productSubmitBtn.textContent = "Create Product";
    }

    productModal.style.display = "flex";
}

function closeModal() {
    productModal.style.display = "none";
}

function renderProducts(products) {
    productsTableBody.innerHTML = "";

    if (!products || products.length === 0) {
        productsTableBody.innerHTML = `
            <tr>
                <td colspan="7" class="py-14 text-center">
                    <i class="ph ph-package text-3xl text-[#171E26]/20"></i>
                    <p class="font-inter text-sm text-[#171E26]/45 mt-2">No products found</p>
                </td>
            </tr>
        `;
        return;
    }

    products.forEach(function (product) {
        const tr = document.createElement("tr");
        tr.className = "border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition";

        const imageHtml = product.image_url
            ? `<img src="${product.image_url}" alt="${product.name}" class="w-9 h-9 object-cover rounded-lg flex-shrink-0">`
            : `<div class="w-9 h-9 rounded-lg bg-[#F7FAFD] flex items-center justify-center flex-shrink-0"><i class="ph ph-package text-[#171E26]/25 text-base"></i></div>`;

        tr.innerHTML = `
      <td class="py-3 px-3">
        <div class="flex items-center gap-3">
          ${imageHtml}
          <strong class="font-inter text-[14px] font-semibold text-[#171E26]">${product.name}</strong>
        </div>
      </td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${product.category ? product.category.name : "N/A"}</td>
      <td class="py-3 px-3 font-inter text-[13px] text-[#171E26]/60">${product.barcode || "N/A"}</td>
      <td class="py-3 px-3 font-inter text-[14px] font-semibold text-[#171E26]">${formatCurrency(product.price)}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${product.stock_quantity || 0}</td>
      <td class="py-3 px-3">
        <span class="font-inter text-[11px] font-semibold px-2.5 py-1 rounded-full ${stockBadge(product).classes}">
          ${stockBadge(product).label}
        </span>
      </td>
      <td class="py-3 px-3">
        <div class="flex gap-2">
          <button type="button" onclick="viewProduct(${product.id})"
                  class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#171E26] hover:bg-[#F7FAFD] transition">
            View
          </button>
          <button type="button" onclick='editProduct(${JSON.stringify(product)})'
                  class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition">
            Edit
          </button>
          <button type="button" onclick="deleteProduct(${product.id})"
                  class="rounded-lg border border-red-200 px-3 py-1.5 font-inter text-[13px] font-semibold text-red-500 hover:bg-red-50 transition">
            Delete
          </button>
        </div>
      </td>
    `;
        productsTableBody.appendChild(tr);
    });
}

function renderPagination() {
    paginationContainer.innerHTML = "";

    if (totalPages <= 1) return;

    const prevBtn = document.createElement("button");
    prevBtn.className = "rounded-lg border border-[#DBEBFB] px-4 py-2 font-inter text-sm font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed transition";
    prevBtn.textContent = "Previous";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = function () {
        loadProducts(currentPage - 1);
    };
    paginationContainer.appendChild(prevBtn);

    const pageInfo = document.createElement("span");
    pageInfo.className = "mx-3 font-inter text-sm text-[#171E26]/60";
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    paginationContainer.appendChild(pageInfo);

    const nextBtn = document.createElement("button");
    nextBtn.className = "rounded-lg border border-[#DBEBFB] px-4 py-2 font-inter text-sm font-semibold text-[#171E26] hover:bg-[#F7FAFD] disabled:opacity-40 disabled:cursor-not-allowed transition";
    nextBtn.textContent = "Next";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = function () {
        loadProducts(currentPage + 1);
    };
    paginationContainer.appendChild(nextBtn);
}

async function loadCategories() {
    try {
        const data = await Api.get("/staff/product-categories");
        const categories = data.data || data;

        categories.forEach(function (category) {
            const option = document.createElement("option");
            option.value = category.id;
            option.textContent = category.name;
            categoryFilter.appendChild(option);

            const formOption = document.createElement("option");
            formOption.value = category.id;
            formOption.textContent = category.name;
            productCategoryInput.appendChild(formOption);
        });
    } catch (error) {
        console.error("Unable to load categories:", error);
    }
}

async function loadProducts(page = 1) {
    if (!Auth.requireAuth()) return;

    productsLoading.style.display = "block";
    productsContent.style.display = "none";
    productsError.style.display = "none";

    try {
        if (availabilityFilter.value) {
            // The API's `availability` param only reflects the manual
            // is_available flag, not actual stock_quantity — but the Status
            // badge in this table prioritizes stock_quantity (a product with
            // 0 stock always shows "Out of Stock" regardless of
            // is_available, see stockBadge()). Filtering must match what's
            // actually displayed, so when this filter is active we fetch the
            // full (search/category-filtered) catalog and filter + paginate
            // client-side instead of relying on the server's availability
            // param, which gave inconsistent results with what the table
            // showed. Trade-off: this fetches more data upfront than normal
            // server pagination, since there's no server-side stock-status
            // filter to rely on.
            await loadProductsFilteredByStock(page);
        } else {
            await loadProductsFromServer(page);
        }
    } catch (error) {
        productsLoading.style.display = "none";
        productsError.textContent = error.message || "Unable to load products.";
        productsError.style.display = "flex";
    }
}

async function loadProductsFromServer(page) {
    currentPage = page;

    const params = new URLSearchParams();
    params.append("page", currentPage);
    params.append("per_page", 20);

    if (searchInput.value.trim()) {
        params.append("search", searchInput.value.trim());
    }
    if (categoryFilter.value) {
        params.append("category_id", categoryFilter.value);
    }

    const data = await Api.get(`/staff/products?${params.toString()}`);
    renderProducts(data.data);
    totalPages = data.meta ? data.meta.last_page : 1;
    renderPagination();
    productsLoading.style.display = "none";
    productsContent.style.display = "block";
}

async function loadProductsFilteredByStock(page) {
    // Walk every page of the catalog (respecting search/category filters)
    // since there's no server-side stock-status filter to rely on.
    const perPage = 100;
    let fetchPage = 1;
    let lastPage = 1;
    let all = [];

    do {
        const params = new URLSearchParams();
        params.append("page", fetchPage);
        params.append("per_page", perPage);
        if (searchInput.value.trim()) {
            params.append("search", searchInput.value.trim());
        }
        if (categoryFilter.value) {
            params.append("category_id", categoryFilter.value);
        }

        const data = await Api.get(`/staff/products?${params.toString()}`);
        all = all.concat(data.data || []);
        lastPage = (data.meta && data.meta.last_page) || 1;
        fetchPage += 1;
    } while (fetchPage <= lastPage);

    // Same priority logic as stockBadge(): zero stock always counts as
    // "Out of Stock" regardless of is_available.
    const wantOutOfStock = availabilityFilter.value === "0";
    const filtered = all.filter(function (p) {
        const outOfStock = Number(p.stock_quantity) <= 0;
        return wantOutOfStock ? outOfStock : (!outOfStock && p.is_available);
    });

    // Paginate the filtered results ourselves (20 per page, matching the
    // normal server page size) since this is now happening in-memory.
    const perPageClient = 20;
    totalPages = Math.max(1, Math.ceil(filtered.length / perPageClient));
    currentPage = Math.min(page, totalPages);
    const start = (currentPage - 1) * perPageClient;
    const pageItems = filtered.slice(start, start + perPageClient);

    renderProducts(pageItems);
    renderPagination();
    productsLoading.style.display = "none";
    productsContent.style.display = "block";
}

window.viewProduct = function (id) {
    window.location.href = `/staff/product-details?id=${id}`;
};

window.deleteProduct = async function (id) {
    if (!(await UIModal.confirm('Are you sure you want to delete this product? This cannot be undone.', { danger: true }))) return;

    try {
        await Api.delete(`/staff/products/${id}`);
        loadProducts(currentPage);
    } catch (error) {
        await UIModal.alert(error.message || 'Unable to delete product.');
    }
};

window.editProduct = function (product) {
    productNameInput.disabled = false;
    productGenericNameInput.disabled = false;
    productCategoryInput.disabled = false;
    productPriceInput.disabled = false;
    productReorderLevelInput.disabled = false;
    productDescriptionInput.disabled = false;
    productBarcodeInput.disabled = false;
    productRequiresPrescriptionInput.disabled = false;
    productImageInput.disabled = false;
    productSubmitBtn.style.display = "inline-flex";
    openModal("Edit Product", product);
};

createProductBtn.addEventListener("click", function () {
    productNameInput.disabled = false;
    productGenericNameInput.disabled = false;
    productCategoryInput.disabled = false;
    productPriceInput.disabled = false;
    productReorderLevelInput.disabled = false;
    productDescriptionInput.disabled = false;
    productBarcodeInput.disabled = false;
    productRequiresPrescriptionInput.disabled = false;
    productImageInput.disabled = false;
    productSubmitBtn.style.display = "inline-flex";
    openModal("Create Product");
});

closeProductModalBtn.addEventListener("click", closeModal);
cancelProductModalBtn.addEventListener("click", closeModal);

productModal.addEventListener("click", function (event) {
    if (event.target === productModal) {
        closeModal();
    }
});

productImageInput.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            productImagePreview.src = e.target.result;
            productImagePreview.style.display = "block";
        };
        reader.readAsDataURL(file);
    }
});

productForm.addEventListener("submit", async function (event) {
    event.preventDefault();
    productSubmitBtn.disabled = true;
    productFormError.style.display = "none";

    const name = productNameInput.value.trim();
    const barcode = productBarcodeInput.value.trim();
    const editingId = productIdInput.value ? Number(productIdInput.value) : null;

    // Client-side duplicate pre-check. This is best-effort only — the API's
    // StoreProductRequest has no unique validation on name/barcode at all,
    // so this can't fully prevent duplicates (e.g. a race condition between
    // two staff creating at the same moment would still slip through), but
    // it catches the common case of accidentally re-creating an existing
    // product. The real fix is adding unique:products,name and/or
    // unique:products,barcode validation on the backend.
    try {
        const checkParams = new URLSearchParams();
        checkParams.append("search", name);
        checkParams.append("per_page", 20);
        const checkData = await Api.get(`/staff/products?${checkParams.toString()}`);
        const existing = (checkData.data || []).find(function (p) {
            if (editingId && p.id === editingId) return false;
            const nameMatches = p.name && p.name.trim().toLowerCase() === name.toLowerCase();
            const barcodeMatches = barcode && p.barcode && p.barcode.trim() === barcode;
            return nameMatches || barcodeMatches;
        });

        if (existing) {
            const isBarcodeClash = barcode && existing.barcode && existing.barcode.trim() === barcode;
            productFormError.textContent = isBarcodeClash
                ? `A product with barcode "${barcode}" already exists (${existing.name}).`
                : `A product named "${existing.name}" already exists.`;
            productFormError.style.display = "flex";
            productSubmitBtn.disabled = false;
            return;
        }
    } catch (error) {
        console.error("Duplicate check failed, proceeding with submission:", error);
        // Don't block submission just because the pre-check itself failed —
        // fall through to the normal save flow below.
    }

    const formData = new FormData();
    formData.append("name", productNameInput.value.trim());
    formData.append("generic_name", productGenericNameInput.value.trim());

    if (productCategoryInput.value) {
        formData.append("product_category_id", productCategoryInput.value);
    }

    formData.append("price", productPriceInput.value);
    formData.append("reorder_level", productReorderLevelInput.value || "0");
    formData.append("description", productDescriptionInput.value.trim());
    formData.append("barcode", productBarcodeInput.value.trim());
    formData.append(
        "requires_prescription",
        productRequiresPrescriptionInput.checked ? "1" : "0",
    );

    if (productImageInput.files[0]) {
        formData.append("image", productImageInput.files[0]);
    }

    try {
        const productId = productIdInput.value;
        const token = Api.getToken();

        let url = "/api/v1/staff/products";
        let method = "POST";

        if (productId) {
            url = `/api/v1/staff/products/${productId}`;
            formData.append("_method", "PATCH");
        }

        const response = await fetch(url, {
            method: "POST",
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            },
            body: formData,
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                const messages = [];
                Object.keys(data.errors).forEach(function (key) {
                    messages.push(...data.errors[key]);
                });
                productFormError.textContent = messages.join(", ");
            } else {
                productFormError.textContent =
                    data.message || "Unable to save product.";
            }
            productFormError.style.display = "flex";
            return;
        }

        closeModal();
        loadProducts();
    } catch (error) {
        productFormError.textContent =
            error.message || "Unable to save product.";
        productFormError.style.display = "flex";
    } finally {
        productSubmitBtn.disabled = false;
    }
});
searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function () {
        loadProducts(1);
    }, 500);
});

categoryFilter.addEventListener("change", function () {
    loadProducts(1);
});

availabilityFilter.addEventListener("change", function () {
    loadProducts(1);
});

loadCategories();
loadProducts();