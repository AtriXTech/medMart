<x-layouts.customer title="Notifications" active="notifications">
    <div style="margin-bottom: 16px;">
        <button class="btn btn-secondary btn-block" id="mark-all-read">Mark All as Read</button>
    </div>

    <div class="alert alert-error" id="notifications-error" style="display: none;"></div>
    <div id="notifications-loading" class="loading-state">Loading notifications...</div>
    <div id="notifications-content" style="display: none;"></div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/customer/notifications.js') }}"></script>
    </x-slot:scripts>
</x-layouts.customer>