<x-layouts.customer title="Upload Prescription" active="prescriptions">
    <div class="alert alert-error" id="upload-error" style="display: none;"></div>
    <div class="alert alert-success" id="upload-success" style="display: none;"></div>

    <div class="card">
        <p class="section-title">Upload Prescription</p>
        <form id="upload-form">
            <div class="field">
                <label for="file">Prescription File (JPG, PNG, or PDF)</label>
                <input type="file" id="file" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" id="upload-submit">Upload</button>
        </form>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/upload-prescription.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>