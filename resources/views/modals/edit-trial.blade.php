<div class="modal fade" id="editTrialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Edit Trial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTrialForm">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label class="form-label">Feature *</label>
                        <select class="form-control" name="feature_id" id="editTrialFeature" required>
                            <option value="">Select Feature</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client Name *</label>
                        <input type="text" class="form-control" name="client_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date *</label>
                        <input type="datetime-local" class="form-control" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date *</label>
                        <input type="datetime-local" class="form-control" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select class="form-control" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="expired">Expired</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateTrial()">Save</button>
            </div>
        </div>
    </div>
</div>
