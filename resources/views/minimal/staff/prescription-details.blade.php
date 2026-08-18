<x-layouts.staff title="Prescription Details" active="prescriptions">
    <div class="alert alert-error" id="prescription-error" style="display: none;"></div>

    <div id="prescription-loading" class="loading-state">Loading prescription details...</div>

    <div id="prescription-content" style="display: none;">
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <p class="section-title" style="margin: 0;">Prescription Information</p>
                <div style="display: flex; gap: 8px;">
                    <a href="/staff/prescriptions" class="btn btn-secondary">Back</a>
                    <button class="btn btn-success" id="approve-btn" style="display: none;">Approve</button>
                    <button class="btn btn-danger" id="reject-btn" style="display: none;">Reject</button>
                </div>
            </div>
            <div id="prescription-info"></div>
        </div>
    </div>

    <div id="review-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Review Prescription</h3>
                <button type="button" class="close-btn" id="close-review-btn">&times;</button>
            </div>
            <div class="alert alert-error" id="review-error" style="display: none;"></div>
            <form id="review-form">
                <input type="hidden" id="review-status">
                <div class="field">
                    <label for="review-notes">Notes</label>
                    <textarea id="review-notes" rows="4" placeholder="Add review notes..."></textarea>
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="cancel-review-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="review-submit-btn">Submit Review</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:scripts>
        <script src="{{ asset('assets/minimal/js/staff/prescription-details.js') }}"></script>
    </x-slot:scripts>
</x-layouts.staff>