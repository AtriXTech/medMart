/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: loadCategories() (GET /staff/product-categories), openModal()/
    closeModal() and the create/edit #category-modal + its submit handler
    (same POST/PATCH /staff/product-categories endpoints, same payload,
    same 422 handling), renderCategories(), the Edit action, and every
    existing element ID.
  - CHANGED: window.deleteCategory — no longer calls native confirm(). It
    now opens the new #confirm-modal and only runs the actual
    Api.delete('/staff/product-categories/:id') call (same endpoint, same
    error handling/alert() on failure — that part is untouched) if the
    modal's Delete button is clicked.
  - NEW: confirm modal DOM refs + showConfirmModal()/closeConfirmModal(),
    same interaction pattern used on Staff Management's and the
    Subscription page's confirm modals (set confirmModalAction, show
    modal, run the action only on confirm, clear it on cancel/backdrop
    click).
*/

const categoriesError = document.getElementById('categories-error');
const categoriesLoading = document.getElementById('categories-loading');
const categoriesContent = document.getElementById('categories-content');
const categoriesTableBody = document.getElementById('categories-table-body');
const createCategoryBtn = document.getElementById('create-category-btn');
const categoryModal = document.getElementById('category-modal');
const categoryForm = document.getElementById('category-form');
const categoryFormTitle = document.getElementById('category-form-title');
const categoryNameInput = document.getElementById('category-name');
const categoryIdInput = document.getElementById('category-id');
const categoryFormError = document.getElementById('category-form-error');
const categorySubmitBtn = document.getElementById('category-submit-btn');
const closeModalBtn = document.getElementById('close-modal-btn');
const cancelModalBtn = document.getElementById('cancel-modal-btn');

// NEW — confirm modal elements (used only by the Delete category action)
const confirmModal = document.getElementById('confirm-modal');
const confirmModalTitle = document.getElementById('confirm-modal-title');
const confirmModalMessage = document.getElementById('confirm-modal-message');
const confirmModalCancelBtn = document.getElementById('confirm-modal-cancel-btn');
const confirmModalConfirmBtn = document.getElementById('confirm-modal-confirm-btn');

function openModal(title, category = null) {
  categoryFormTitle.textContent = title;
  categoryFormError.style.display = 'none';
  categoryFormError.textContent = '';

  if (category) {
    categoryIdInput.value = category.id;
    categoryNameInput.value = category.name;
    categorySubmitBtn.textContent = 'Update Category';
  } else {
    categoryIdInput.value = '';
    categoryNameInput.value = '';
    categorySubmitBtn.textContent = 'Create Category';
  }

  categoryModal.style.display = 'flex';
}

function closeModal() {
  categoryModal.style.display = 'none';
}

/* ---------------- NEW: generic confirm modal (Delete category only) ---------------- */

let confirmModalAction = null;

function showConfirmModal({ title, message, confirmText }) {
  confirmModalTitle.textContent = title;
  confirmModalMessage.textContent = message;
  confirmModalConfirmBtn.textContent = confirmText;
  confirmModal.style.display = 'flex';
}

function closeConfirmModal() {
  confirmModal.style.display = 'none';
  confirmModalAction = null;
}

confirmModalCancelBtn.addEventListener('click', closeConfirmModal);
confirmModal.addEventListener('click', function(event) {
  if (event.target === confirmModal) closeConfirmModal();
});
confirmModalConfirmBtn.addEventListener('click', function() {
  const action = confirmModalAction;
  closeConfirmModal();
  if (action) action();
});

function renderCategories(categories) {
  categoriesTableBody.innerHTML = '';

  if (!categories || categories.length === 0) {
    categoriesTableBody.innerHTML = `
      <tr>
        <td colspan="3" class="py-12 text-center">
          <i class="ph ph-tag text-3xl text-[#171E26]/20"></i>
          <p class="font-inter text-sm text-[#171E26]/45 mt-2">No categories found</p>
        </td>
      </tr>
    `;
    return;
  }

  categories.forEach(function(category) {
    const tr = document.createElement('tr');
    tr.className = 'border-b border-[#EAF1FB] hover:bg-[#F7FAFD] transition';
    tr.innerHTML = `
      <td class="py-3 px-3 font-inter text-[14px] font-medium text-[#171E26]">${category.name}</td>
      <td class="py-3 px-3 font-inter text-[14px] text-[#171E26]/70">${category.products_count || 0}</td>
      <td class="py-3 px-3">
        <div class="flex gap-2">
          <button type="button" onclick='editCategory(${JSON.stringify(category)})'
                  class="rounded-lg border border-[#DBEBFB] px-3 py-1.5 font-inter text-[13px] font-semibold text-[#2775E4] hover:bg-[#DBEBFB] transition">
            Edit
          </button>
          <button type="button" onclick="deleteCategory(${category.id})"
                  class="rounded-lg border border-red-200 px-3 py-1.5 font-inter text-[13px] font-semibold text-red-500 hover:bg-red-50 transition">
            Delete
          </button>
        </div>
      </td>
    `;
    categoriesTableBody.appendChild(tr);
  });
}

async function loadCategories() {
  if (!Auth.requireAuth()) return;

  categoriesLoading.style.display = 'block';
  categoriesContent.style.display = 'none';
  categoriesError.style.display = 'none';

  try {
    const data = await Api.get('/staff/product-categories');
    renderCategories(data.data || data);
    categoriesLoading.style.display = 'none';
    categoriesContent.style.display = 'block';
  } catch (error) {
    categoriesLoading.style.display = 'none';
    categoriesError.textContent = error.message || 'Unable to load categories.';
    categoriesError.style.display = 'flex';
  }
}

window.editCategory = function(category) {
  openModal('Edit Category', category);
};

/* CHANGED: now opens the custom confirm modal instead of native confirm() */
window.deleteCategory = function(id) {
  confirmModalAction = async function() {
    try {
      await Api.delete(`/staff/product-categories/${id}`);
      loadCategories();
    } catch (error) {
      alert(error.message || 'Unable to delete category.');
    }
  };

  showConfirmModal({
    title: 'Delete category?',
    message: 'Are you sure you want to delete this category?',
    confirmText: 'Delete',
  });
};

createCategoryBtn.addEventListener('click', function() {
  openModal('Create Category');
});

closeModalBtn.addEventListener('click', closeModal);
cancelModalBtn.addEventListener('click', closeModal);

categoryModal.addEventListener('click', function(event) {
  if (event.target === categoryModal) {
    closeModal();
  }
});

categoryForm.addEventListener('submit', async function(event) {
  event.preventDefault();
  categorySubmitBtn.disabled = true;
  categoryFormError.style.display = 'none';

  const formData = {
    name: categoryNameInput.value.trim()
  };

  try {
    const categoryId = categoryIdInput.value;
    if (categoryId) {
      await Api.patch(`/staff/product-categories/${categoryId}`, formData);
    } else {
      await Api.post('/staff/product-categories', formData);
    }
    closeModal();
    loadCategories();
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      const messages = [];
      Object.keys(error.data.errors).forEach(function(key) {
        messages.push(...error.data.errors[key]);
      });
      categoryFormError.textContent = messages.join(', ');
    } else {
      categoryFormError.textContent = error.message || 'Unable to save category.';
    }
    categoryFormError.style.display = 'flex';
  } finally {
    categorySubmitBtn.disabled = false;
  }
});

loadCategories();