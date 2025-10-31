<div class="modal fade" id="editSoftwareModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Edit Software</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSoftwareForm">
                    <input type="hidden" id="editSoftwareId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Software Name *</label>
                        <input type="text" class="form-control" id="editSoftwareName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Software URL</label>
                        <input type="url" class="form-control" id="editSoftwareUrl" name="url">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateSoftware()">Save</button>
            </div>
        </div>
    </div>
</div>