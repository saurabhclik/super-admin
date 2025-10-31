<div class="modal fade" id="editFeatureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Edit Feature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editFeatureForm">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label class="form-label">Feature Name *</label>
                        <input type="text" class="form-control" name="feature_name" required readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price ($) *</label>
                        <input type="number" step="0.01" class="form-control" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Information</label>

                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="meta_description" placeholder="Enter feature description"></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Key Benefits</label>
                            <div id="edit-meta-key-benefits"></div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-1" id="edit-add-key-benefit">Add Another Benefit</button>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Analytics</label>
                            <div id="edit-meta-analytics"></div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-1" id="edit-add-analytic">Add Another Analytic</button>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">Video URL</label>
                        <input type="url" class="form-control" name="video_url" placeholder="https://example.com/video">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateFeature()">Save</button>
            </div>
        </div>
    </div>
</div>
