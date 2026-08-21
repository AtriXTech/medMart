/*
  CHANGE SUMMARY (vs. previous version):
  - UNCHANGED: openModal(), closeModal(), loadSuppliers() (same endpoint
    GET /staff/suppliers, same data.data || data fallback), the create/edit
    submit handler (same POST /staff/suppliers and PATCH /staff/suppliers/:id,
    same 422 error handling), window.editSupplier / window.deleteSupplier
    (unchanged, including the onclick='editSupplier(${JSON.stringify(...)})'
    pattern in the rendered rows — flagging as a pre-existing fragility:
    a supplier name containing a single quote could break that attribute,
    but I didn't change it since that's a functional fix, not a style one,
    and you asked me not to touch logic. Say the word if you want that
    hardened later.)
  - CHANGED (presentation only): renderSuppliers() now emits Tailwind-styled
    rows instead of plain <td> text, with proper Edit/Delete action buttons.
*/

const suppliersError = document.getElementById('suppliers-error');
const suppliersLoading = document.getElementById('suppliers-loading');
const suppliersContent = document.getElementById('suppliers-content');
const suppliersTableBody = document.getElementById('suppliers-table-body');
const createSupplierBtn = document.getElementById('create-supplier-btn');
const supplierModal = document.getElementById('supplier-modal');
const supplierForm = document.getElementById('supplier-form');
const supplierFormTitle = document.getElementById('supplier-form-title');
const supplierNameInput = document.getElementById('supplier-name');
const supplierContactNameInput = document.getElementById('supplier-contact-name');
const supplierEmailInput = document.getElementById('supplier-email');
const supplierPhoneInput = document.getElementById('supplier-phone');
const supplierAddressInput = document.getElementById('supplier-address');
const supplierIdInput = document.getElementById('supplier-id');
const supplierFormError = document.getElementById('supplier-form-error');
const supplierSubmitBtn = document.getElementById('supplier-submit-btn');
const closeSupplierModalBtn = document.getElementById('close-supplier-modal-btn');
const cancelSupplierModalBtn = document.getElementById('cancel-supplier-modal-btn');

function openModal(title, supplier = null) {
  supplierFormTitle.textContent = title;
  supplierFormError.style.display = 'none';
  supplierFormError.textContent = '';

  if (supplier) {
    supplierIdInput.value = supplier.id;
    supplierNameInput.value = supplier.name;
    supplierContactNameInput.value = supplier.contact_name || '';
    supplierEmailInput.value = supplier.email || '';
    supplierPhoneInput.value = supplier.phone || '';
    supplierAddressInput.value = supplier.address || '';
    supplierSubmitBtn.textContent = 'Update Supplier';
  } else {
    supplierIdInput.value = '';
    supplierNameInput.value = '';
    supplierContactNameInput.value = '';
    supplierEmailInput.value = '';
    supplierPhoneInput.value = '';
    supplierAddressInput.value = '';
    supplierSubmitBtn.textContent = 'Create Supplier';
  }

  supplierModal.style.display = 'flex';
}

function closeModal() {
  supplierModal.style.display = 'none';
}

function renderSuppliers(suppliers) {
  if (!suppliers || suppliers.length === 0) {
    suppliersTableBody.innerHTML = `<tr><td colspan="5" class="text-center py-14">
      <i class="ph-light ph-truck text-3xl text-[#171E26]/20 block mb-2"></i>
      <p class="font-inter text-[13px] text-[#171E26]/45">No suppliers found</p>
    </td></tr>`;
    return;
  }

  suppliersTableBody.innerHTML = suppliers.map(function (supplier) {
    const cell = function (value) {
      return value
        ? `<span class="text-[#171E26]/70">${value}</span>`
        : `<span class="text-[#171E26]/30">N/A</span>`;
    };

    return `<tr class="table-row border-b border-[#F3F7FC] last:border-0">
      <td class="py-3 pr-4 font-inter text-[13px] font-semibold text-[#171E26]">${supplier.name}</td>
      <td class="py-3 pr-4 font-inter text-[13px]">${cell(supplier.contact_name)}</td>
      <td class="py-3 pr-4 font-inter text-[13px]">${cell(supplier.email)}</td>
      <td class="py-3 pr-4 font-inter text-[13px]">${cell(supplier.phone)}</td>
      <td class="py-3 text-right whitespace-nowrap">
        <button onclick='editSupplier(${JSON.stringify(supplier)})'
          class="px-3 py-1.5 rounded-lg border border-[#DBEBFB] font-inter text-[12px] font-semibold text-[#171E26] hover:bg-[#F7FAFD]">Edit</button>
        <button onclick="deleteSupplier(${supplier.id})"
          class="px-3 py-1.5 rounded-lg font-inter text-[12px] font-semibold text-[#9C3A32] hover:bg-[#FDEDEC] ml-1.5">Delete</button>
      </td>
    </tr>`;
  }).join('');
}

async function loadSuppliers() {
  if (!Auth.requireAuth()) return;

  suppliersLoading.style.display = 'block';
  suppliersContent.style.display = 'none';
  suppliersError.style.display = 'none';

  try {
    const data = await Api.get('/staff/suppliers');
    renderSuppliers(data.data || data);
    suppliersLoading.style.display = 'none';
    suppliersContent.style.display = 'block';
  } catch (error) {
    suppliersLoading.style.display = 'none';
    suppliersError.textContent = error.message || 'Unable to load suppliers.';
    suppliersError.style.display = 'block';
  }
}

window.editSupplier = function (supplier) {
  openModal('Edit Supplier', supplier);
};

window.deleteSupplier = async function (id) {
  if (!confirm('Are you sure you want to delete this supplier?')) return;

  try {
    await Api.delete(`/staff/suppliers/${id}`);
    loadSuppliers();
  } catch (error) {
    alert(error.message || 'Unable to delete supplier.');
  }
};

createSupplierBtn.addEventListener('click', function () {
  openModal('Create Supplier');
});

closeSupplierModalBtn.addEventListener('click', closeModal);
cancelSupplierModalBtn.addEventListener('click', closeModal);

supplierModal.addEventListener('click', function (event) {
  if (event.target === supplierModal) {
    closeModal();
  }
});

supplierForm.addEventListener('submit', async function (event) {
  event.preventDefault();
  supplierSubmitBtn.disabled = true;
  supplierFormError.style.display = 'none';

  const formData = {
    name: supplierNameInput.value.trim(),
    contact_name: supplierContactNameInput.value.trim(),
    email: supplierEmailInput.value.trim(),
    phone: supplierPhoneInput.value.trim(),
    address: supplierAddressInput.value.trim()
  };

  try {
    const supplierId = supplierIdInput.value;
    if (supplierId) {
      await Api.patch(`/staff/suppliers/${supplierId}`, formData);
    } else {
      await Api.post('/staff/suppliers', formData);
    }
    closeModal();
    loadSuppliers();
  } catch (error) {
    if (error.status === 422 && error.data && error.data.errors) {
      const messages = [];
      Object.keys(error.data.errors).forEach(function (key) {
        messages.push(...error.data.errors[key]);
      });
      supplierFormError.textContent = messages.join(', ');
    } else {
      supplierFormError.textContent = error.message || 'Unable to save supplier.';
    }
    supplierFormError.style.display = 'block';
  } finally {
    supplierSubmitBtn.disabled = false;
  }
});

loadSuppliers();