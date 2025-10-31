<div class="modal fade" id="uploadApkModal" tabindex="-1" aria-labelledby="uploadApkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title text-light" id="uploadApkModalLabel">
                    <i class="fas fa-upload me-2"></i>Upload APK File
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadApkForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apkVersion" class="form-label">Version *</label>
                                <input type="text" class="form-control" id="apkVersion" name="version" 
                                    placeholder="e.g., 1.0.0" required>
                                <div class="form-text">Enter the version number for this APK</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apkFile" class="form-label">APK File *</label>
                                <input type="file" class="form-control" id="apkFile" name="apk_file" 
                                    accept=".apk" required>
                                <div class="form-text">Maximum file size: 100MB</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="apkDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="apkDescription" name="description" 
                        rows="3" placeholder="Enter description about this APK version..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="uploadApk()">
                    <i class="fas fa-upload me-1"></i>Upload APK
                </button>
            </div>
        </div>
    </div>
</div>