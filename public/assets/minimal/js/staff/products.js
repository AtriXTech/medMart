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
        productsTableBody.innerHTML =
            '<tr><td colspan="8" class="empty-state">No products found</td></tr>';
        return;
    }

    products.forEach(function (product) {
        const tr = document.createElement("tr");
        tr.innerHTML = `
      <td>
        <div style="display: flex; align-items: center; gap: 10px;">
          ${
              product.image_url
                  ? `<img src="${product.image_url}" alt="${product.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">`
                  : `<div style="width: 40px; height: 40px; background: var(--bg); border-radius: 4px; display: flex; align-items: center; justify-content: center;">📦</div>`
          }
          <strong>${product.name}</strong>
        </div>
      </td>
      <td>${product.category ? product.category.name : "N/A"}</td>
      <td>${product.barcode || "N/A"}</td>
      <td>${formatCurrency(product.price)}</td>
      <td>${product.stock_quantity || 0}</td>
      <td>
        <span class="badge ${product.is_available ? "badge-success" : "badge-danger"}">
          ${product.is_available ? "Available" : "Unavailable"}
        </span>
      </td>
      <td>
        <button class="btn btn-secondary" onclick="viewProduct(${product.id})">View</button>
        <button class="btn btn-secondary" onclick='editProduct(${JSON.stringify(product)})'>Edit</button>
      </td>
    `;
        productsTableBody.appendChild(tr);
    });
}

function renderPagination() {
    paginationContainer.innerHTML = "";

    if (totalPages <= 1) return;

    const prevBtn = document.createElement("button");
    prevBtn.className = "btn btn-secondary";
    prevBtn.textContent = "Previous";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = function () {
        loadProducts(currentPage - 1);
    };
    paginationContainer.appendChild(prevBtn);

    const pageInfo = document.createElement("span");
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    pageInfo.style.margin = "0 12px";
    paginationContainer.appendChild(pageInfo);

    const nextBtn = document.createElement("button");
    nextBtn.className = "btn btn-secondary";
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

    currentPage = page;
    productsLoading.style.display = "block";
    productsContent.style.display = "none";
    productsError.style.display = "none";

    const params = new URLSearchParams();
    params.append("page", currentPage);
    params.append("per_page", 20);

    if (searchInput.value.trim()) {
        params.append("search", searchInput.value.trim());
    }
    if (categoryFilter.value) {
        params.append("category_id", categoryFilter.value);
    }

    try {
        const data = await Api.get(`/staff/products?${params.toString()}`);
        renderProducts(data.data);
        totalPages = data.meta ? data.meta.last_page : 1;
        renderPagination();
        productsLoading.style.display = "none";
        productsContent.style.display = "block";
    } catch (error) {
        productsLoading.style.display = "none";
        productsError.textContent = error.message || "Unable to load products.";
        productsError.style.display = "block";
    }
}

window.viewProduct = function (id) {
    window.location.href = `/staff/product-details?id=${id}`;
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
            productFormError.style.display = "block";
            return;
        }

        closeModal();
        loadProducts();
    } catch (error) {
        productFormError.textContent =
            error.message || "Unable to save product.";
        productFormError.style.display = "block";
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
