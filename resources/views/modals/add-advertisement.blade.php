<div class="modal fade" id="addAdvertisementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Add Advertisement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAdvertisementForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" name="title" placeholder="Enter title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Subtitle</label>
                                <input type="text" class="form-control" name="subtitle" placeholder="Enter subtitle">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Badge Text</label>
                                <input type="text" class="form-control" name="badge_text" placeholder="e.g., New, Popular, Limited">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Media URL</label>
                                <input type="url" class="form-control" name="media" placeholder="Image or video URL">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Main description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Features (JSON array)</label>
                        <textarea class="form-control" name="features" rows="3" placeholder='["Feature 1", "Feature 2", "Feature 3"]'></textarea>
                        <small class="text-muted">Enter features as JSON array format</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pricing Information</label>
                        <textarea class="form-control" name="pricing" rows="2" placeholder="e.g., $99/month, Free trial available"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" class="form-control" name="button" placeholder="e.g., Get Started, Learn More">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Footer Note</label>
                                <input type="text" class="form-control" name="footer_note" placeholder="e.g., No credit card required">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" name="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="createAdvertisement()">Save Advertisement</button>
            </div>
        </div>
    </div>
</div>