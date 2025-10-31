<div class="modal fade" id="editFaqModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Edit FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editFaqForm">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label class="form-label">Question *</label>
                        <input type="text" class="form-control" name="question" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Answer *</label>
                        <textarea class="form-control" name="answer" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateFaq()">Save</button>
            </div>
        </div>
    </div>
</div>
