
<div class="modal fade" id="ticketMessagesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Ticket Details - <span id="ticket-subject"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>Status:</strong> 
                        <span id="ticket-status" class="status-badge status-open ms-2"></span>
                    </div>
                    <div id="ticket-actions">
                        <button class="btn btn-warning btn-sm" id="reopen-ticket" style="display: none;">
                            <i class="fas fa-redo me-1"></i>Reopen Ticket
                        </button>
                        <button class="btn btn-success btn-sm" id="resolve-ticket" style="display: none;">
                            <i class="fas fa-check me-1"></i>Resolve Ticket
                        </button>
                        <button class="btn btn-secondary btn-sm" id="close-ticket" style="display: none;">
                            <i class="fas fa-times me-1"></i>Close Ticket
                        </button>
                    </div>
                </div>
                
                <div id="ticket-attachments" class="mb-3 p-3 border rounded" style="display: none;">
                    <h6>Attachments:</h6>
                    <div id="attachments-list" class="d-flex flex-wrap gap-2">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Admin Remarks</label>
                    <textarea class="form-control" id="ticket-remarks" rows="3" placeholder="Add remarks here..."></textarea>
                    <button type="button" class="btn btn-primary mt-2" id="save-remarks">
                        <i class="fas fa-save me-1"></i>Save Remarks
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>