<div class="modal fade" id="addSoftwareModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Add New Software</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('software.store') }}" method="POST" id="add-software-modal-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Software Name *</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter software name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Software URL</label>
                        <input type="url" class="form-control" name="url" placeholder="https://example.com">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="add-software-modal-form">Add Software</button>
            </div>
        </div>
    </div>
</div>