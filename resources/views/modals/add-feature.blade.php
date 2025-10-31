<div class="modal fade" id="addFeatureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Add Feature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addFeatureForm">
                    <div class="mb-3">
                        <label class="form-label">Feature Name *</label>
                        <input type="text" class="form-control" name="feature_name" placeholder="Enter feature name" required readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price ($) *</label>
                        <input type="number" step="0.01" class="form-control" name="price" placeholder="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Information</label>
                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="meta_description" placeholder="Enter feature description"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Key Benefits</label>
                            <div id="meta-key-benefits">
                                <div class="input-group mb-2 meta-input-group">
                                    <input type="text" class="form-control meta-key-benefit" placeholder="Enter key benefit">
                                    <button type="button" class="btn btn-danger remove-meta-input"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-key-benefit">Add Another Benefit</button>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Analytics</label>
                            <div id="meta-analytics">
                                <div class="input-group mb-2 meta-input-group">
                                    <input type="text" class="form-control meta-analytic" placeholder="Enter analytic metric">
                                    <button type="button" class="btn btn-danger remove-meta-input"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-analytic">Add Another Analytic</button>
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
                <button type="button" class="btn btn-primary" onclick="createFeature()">Save</button>
            </div>
        </div>
    </div>
</div>