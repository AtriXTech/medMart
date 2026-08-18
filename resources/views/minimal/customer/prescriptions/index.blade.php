<x-layouts.customer title="My Prescriptions" active="prescriptions">
    <div style="margin-bottom: 16px;">
        <a href="/customer/prescriptions/upload" class="btn btn-primary btn-block">Upload New Prescription</a>
    </div>

    <div class="alert alert-error" id="prescriptions-error" style="display: none;"></div>
    <div id="prescriptions-loading" class="loading-state">Loading prescriptions...</div>
    <div id="prescriptions-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/prescriptions.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>