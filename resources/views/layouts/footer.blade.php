<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('.select2').select2({
        placeholder: 'Select',
        width: '100%'
    });

    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let currentSoftware = '{{ $selectedSoftware ?? "" }}';
    let currentSoftwareId = '';
    let currentSoftwareUrl = '';
    let currentEditingTrial = null;
    let currentSoftwareType = '';
    let currentTicketId = null;
    let isLoading = false;
    let currentApkSoftwareId = null;

    async function loadSoftwareInfo() 
    {
        if (!currentSoftware) 
        {
            console.log('No software selected');
            return;
        }
    
        try 
        {
            const response = await apiRequest('/software/info');
            if (response.success === 200) 
            {
                currentSoftwareType = response.data.software_type;
                updateSoftwareTypeDisplay();
            } 
            else 
            {
                currentSoftwareType = 'lead_management';
                updateSoftwareTypeDisplay();
            }
        } 
        catch (error) 
        {
            currentSoftwareType = 'lead_management';
            updateSoftwareTypeDisplay();
        }
    }

    function updateSoftwareTypeDisplay() 
    {
        if (currentSoftwareType) 
        {
            $('#software-type-badge').show();
            $('#current-software-type').text(getSoftwareTypeDisplayName(currentSoftwareType));
            $('#changeSoftwareTypeBtn').show();
            
            const badge = $('#software-type-badge');
            badge.removeClass('bg-success bg-info bg-warning bg-primary bg-secondary');
            
            switch(currentSoftwareType) 
            {
                case 'real_state':
                    badge.addClass('bg-primary');
                    break;
                case 'lead_management':
                    badge.addClass('bg-success');
                    break;
                case 'task_management':
                    badge.addClass('bg-warning');
                    break;
                case 'mis_management':
                    badge.addClass('bg-info');
                    break;
                default:
                    badge.addClass('bg-secondary');
            }
        } 
        else 
        {
            $('#software-type-badge').hide();
            $('#changeSoftwareTypeBtn').hide();
        }
    }

    function getSoftwareTypeDisplayName(type) 
    {
        const types = {
            'real_state': 'Real Estate',
            'lead_management': 'Lead Management', 
            'task_management': 'Task Management',
            'mis_management': 'MIS Management'
        };
        return types[type] || type || 'Not Set';
    }

    function showChangeSoftwareTypeModal() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }

        $('#currentSoftwareName').val(currentSoftware);
        $('#currentSoftwareType').val(getSoftwareTypeDisplayName(currentSoftwareType));
        $('#newSoftwareType').val('');
        
        bootstrap.Modal.getOrCreateInstance(document.getElementById('changeSoftwareTypeModal')).show();
    }

    async function changeSoftwareType() 
    {
        const newType = $('#newSoftwareType').val();
        if (!newType) 
        {
            toastr.error('Please select a new software type');
            return;
        }

        if (newType === currentSoftwareType) 
        {
            toastr.warning('Software type is already set to this value');
            return;
        }

        showGlobalLoader('Changing Software Type', 'Updating software type...');

        try 
        {
            const response = await apiRequest('/software/update-type', 'PUT', {
                software_type: newType
            }, true); 

            if (response.success === 200) 
            {
                toastr.success('Software type updated successfully');
                currentSoftwareType = newType;
                updateSoftwareTypeDisplay();
                bootstrap.Modal.getInstance(document.getElementById('changeSoftwareTypeModal')).hide();
            } 
            else 
            {
                toastr.error(response.message || 'Failed to update software type');
            }
        } 
        catch (error) 
        {
            toastr.error('Network error while updating software type');
        } 
        finally 
        {
            hideGlobalLoader();
        }
    }

    async function loadApkInfo() 
    {
        if (!currentSoftware) 
        {
            resetApkSection();
            return;
        }

        $('#current-apk-software').text(currentSoftware);
        try 
        {
            const response = await apiRequest(`/apk/${currentSoftware}/info`);
            if (response.success === 200) 
            {
                displayApkInfo(response.data);
            } 
            else 
            {
                resetApkSection();
            }
        } 
        catch (error) 
        {
            resetApkSection();
        }
    }

    function displayApkInfo(apkInfo) 
    {
        $('#noApkAvailable').addClass('d-none');
        $('#apkInfoSection').removeClass('d-none');

        const apkDetails = $('#apkDetails');
        
        if (apkInfo.has_apk) 
        {
            apkDetails.html(`
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Version:</strong> <span class="badge bg-primary">${apkInfo.version || 'N/A'}</span></p>
                        <p class="mb-2"><strong>File Name:</strong> ${apkInfo.file_name}</p>
                        <p class="mb-2"><strong>File Size:</strong> ${apkInfo.file_size || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Last Updated:</strong> ${new Date(apkInfo.uploaded_at).toLocaleString()}</p>
                        <div class="mt-3">
                            <button class="btn btn-success btn-sm" onclick="downloadApk()">
                                <i class="fas fa-download me-1"></i>Download APK
                            </button>
                        </div>
                    </div>
                </div>
            `);
            $('#deleteApkBtn').show();
        } 
        else 
        {
            apkDetails.html(`
                <div class="text-center py-3">
                    <i class="fas fa-exclamation-circle fa-2x text-warning mb-2"></i>
                    <p class="text-muted mb-0">No APK file uploaded for this software</p>
                </div>
            `);
            $('#deleteApkBtn').hide();
        }
    }

    function resetApkSection() 
    {
        $('#apkInfoSection').addClass('d-none');
        $('#noApkAvailable').removeClass('d-none');
        $('#current-apk-software').text(currentSoftware || 'None selected');
    }

    function uploadApkModal() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }

        $('#uploadApkForm')[0].reset();
        $('#uploadApkSoftwareName').text(currentSoftware);
        
        bootstrap.Modal.getOrCreateInstance(document.getElementById('uploadApkModal')).show();
    }

    async function uploadApk() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }

        const form = document.getElementById('uploadApkForm');
        const formData = new FormData(form);
        const apkFile = formData.get('apk_file');
        if (!apkFile || apkFile.size === 0) 
        {
            toastr.error('Please select an APK file');
            return;
        }
        const fileName = apkFile.name.toLowerCase();
        const allowedExtensions = ['.apk', '.zip'];
        const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));
        
        if (!hasValidExtension) 
        {
            toastr.error('Please select a valid APK file (supported: .apk, .zip)');
            return;
        }
        const maxSize = 100 * 1024 * 1024; 
        if (apkFile.size > maxSize) 
        {
            toastr.error('File size too large. Maximum size is 100MB');
            return;
        }

        showGlobalLoader('Uploading APK', 'Please wait while we upload your APK file');

        try 
        {
            const response = await fetch(`${currentSoftwareUrl}/api/apk/${currentSoftware}/upload?software=${currentSoftware}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData
            });

            const result = await response.json();
            
            if (result.success === 200) 
            {
                toastr.success('APK uploaded successfully');
                bootstrap.Modal.getInstance(document.getElementById('uploadApkModal')).hide();
                loadApkInfo();
            } 
            else 
            {
                toastr.error(result.message || 'Failed to upload APK');
            }
        } 
        catch (error)
        {
            toastr.error('Network error while uploading APK');
        } 
        finally 
        {
            hideGlobalLoader();
        }
    }

    async function deleteApk() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }

        showConfirmationDialog(
            'Delete APK',
            'Are you sure you want to delete the APK file for ' + currentSoftware + '?',
            'warning',
            async () => {
                showGlobalLoader('Deleting APK', 'Please wait while we remove the APK file');
                try 
                {
                    const response = await apiRequest(`/apk/${currentSoftware}/delete`, 'DELETE');
                    if (response.success === 200) 
                    {
                        toastr.success('APK deleted successfully');
                        loadApkInfo();
                    } 
                    else 
                    {
                        toastr.error(response.message || 'Failed to delete APK');
                    }
                } 
                catch (error) 
                {
                    toastr.error('Network error while deleting APK');
                } 
                finally 
                {
                    hideGlobalLoader();
                }
            }
        );
    }

    async function downloadApk() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }

        showGlobalLoader('Preparing Download', 'Getting APK file ready for download');

        try 
        {
            const response = await apiRequest(`/apk/${currentSoftware}/download`);
            
            if (response.success === 200 && response.data.file_path)
            {
                const link = document.createElement('a');
                link.href = response.data.file_path;
                link.download = response.data.file_name;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                toastr.success('APK download started');
            } 
            else 
            {
                toastr.error(response.message || 'Failed to download APK');
            }
        } 
        catch (error)
        {
            toastr.error('Network error while downloading APK');
        } 
        finally 
        {
            hideGlobalLoader();
        }
    }

    function updateFeaturesTableWithApk(features) 
    {
        const tbody = $('#features-body');
        tbody.empty();

        if (features && features.length) 
        {
            features.forEach(feature => {
                const statusClass = feature.status === 'active' ? 'status-active' : 'status-inactive';
                
                const videoHtml = feature.video_url ? 
                    `<a href="${feature.video_url}" target="_blank" class="btn btn-info btn-sm">View Video</a>` : 
                    '<span class="text-muted">No video</span>';

                let metaDisplay = 'No meta data';
                try 
                {
                    const metaData = feature.meta ? JSON.parse(feature.meta) : {};
                    const parts = [];
                    if (metaData.description) parts.push(metaData.description);
                    if (metaData.key_benefits) parts.push(`Benefits: ${metaData.key_benefits}`);
                    if (metaData.analytics) parts.push(`Analytics: ${metaData.analytics}`);
                    metaDisplay = parts.join('; ');
                    if (metaDisplay.length > 100) 
                    {
                        metaDisplay = metaDisplay.substring(0, 100) + '...';
                    }
                } 
                catch (e) 
                {
                    metaDisplay = 'Invalid meta data';
                }

                tbody.append(`
                    <tr>
                        <td>
                            ${feature.feature_name}
                            ${feature.version ? `<br><small class="text-muted">v${feature.version}</small>` : ''}
                        </td>
                        <td>${metaDisplay}</td>
                        <td>$${Number(feature.price).toFixed(2)}</td>
                        <td>${videoHtml}</td>
                        <td><span class="status-badge ${statusClass}">${feature.status}</span></td>
                        <td>
                            <button class="btn btn-xs btn-soft-light" onclick="editFeature(${feature.id})">
                                <i class="fas fa-edit text-warning"></i>
                            </button>
                            <button class="btn btn-xs btn-soft-light" onclick="deleteFeature(${feature.id})">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        } 
        else 
        {
            tbody.html('<tr><td colspan="6" class="empty-state"><i class="fas fa-star"></i><p>No features found</p></td></tr>');
        }
    }

    function initializeGlobalLoader() 
    {
        $('body').prepend(`
            <div class="global-loader" id="globalLoader" style="display: none;">
                <div class="progress-loader" id="progressLoader"></div>
                <div class="loader-content">
                    <div class="loader-spinner"></div>
                    <div class="loader-text">Loading Software Management System</div>
                    <div class="loader-subtext">Please wait while we prepare your dashboard</div>
                </div>
            </div>
        `);
        
        $(window).on('load', function() 
        {
            setTimeout(() => {
                hideGlobalLoader();
            }, 500);
        });
    }

    function showGlobalLoader(message = 'Loading...', submessage = 'Please wait') 
    {
        $('#globalLoader .loader-text').text(message);
        $('#globalLoader .loader-subtext').text(submessage);
        $('#globalLoader').fadeIn(300);
        $('body').addClass('content-loading');
        isLoading = true;
    }

    function hideGlobalLoader() 
    {
        $('#globalLoader').fadeOut(300, function() 
        {
            $('body').removeClass('content-loading');
            isLoading = false;
        });
    } 
    
    $(document).ready(function() 
    {
        initializeGlobalLoader();
        showGlobalLoader('Initializing Dashboard', 'Loading software management system');
        
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };

        const urlParams = new URLSearchParams(window.location.search);
        const softwareFromUrl = urlParams.get('software');
        currentSoftware = softwareFromUrl || currentSoftware;

        if (currentSoftware) 
        {
            const selectedOption = $('#softwareSelect option[value="' + currentSoftware + '"]');
            currentSoftwareId = selectedOption.data('id');
            currentSoftwareUrl = selectedOption.data('url') || '';
            $('#current-software').text(currentSoftware);
            $('#softwareSelect').val(currentSoftware);
            loadSoftwareInfo().then(() => {
                loadAllData();
            });
        }
        else
        {
            hideGlobalLoader(); 
        }

        $('#softwareSelect').on('change', function() 
        {
            const softwareName = $(this).val();
            if (!softwareName) 
            {
                $('#current-software').text('Select Software');
                currentSoftware = '';
                currentSoftwareId = '';
                currentSoftwareUrl = '';
                currentSoftwareType = '';
                updateSoftwareTypeDisplay();
                resetTables();
                window.history.replaceState({}, '', '/software/manage');
                return;
            }

            const selectedOption = $(this).find('option:selected');
            currentSoftware = softwareName;
            currentSoftwareId = selectedOption.data('id');
            currentSoftwareUrl = selectedOption.data('url') || '';
            $('#current-software').text(softwareName);
            window.history.replaceState({}, '', `?software=${encodeURIComponent(softwareName)}`);
            
            loadSoftwareInfo().then(() => {
                loadAllData();
                toastr.success(`Managing: ${softwareName}`);
            });
        });

        $('#changeSoftwareTypeBtn').on('click', showChangeSoftwareTypeModal);
        $('#confirmTypeChange').on('click', changeSoftwareType);

        $('#sidebar-dashboard').on('click', function(e) 
        {
            e.preventDefault();
        });
        $('#sidebar-software').on('click', function(e) 
        {
            e.preventDefault();
            $('html, body').animate({scrollTop: $(".card.mb-4").offset().top}, 500);
        });
        $('#sidebar-features').on('click', function(e)
        {
            e.preventDefault();
            $('#adminTabs button[data-bs-target="#features"]').tab('show');
        });
        $('#sidebar-trials').on('click', function(e) 
        {
            e.preventDefault();
            $('#adminTabs button[data-bs-target="#trials"]').tab('show');
        });
        $('#sidebar-faqs').on('click', function(e) 
        {
            e.preventDefault();
            $('#adminTabs button[data-bs-target="#faqs"]').tab('show');
        });
        $('#sidebar-requests').on('click', function(e) 
        {
            e.preventDefault();
            $('#adminTabs button[data-bs-target="#software-requests"]').tab('show');
        });
        $('#sidebar-ads').on('click', function(e) 
        {
            e.preventDefault();
            $('#adminTabs button[data-bs-target="#advertisements"]').tab('show');
        });
        $('#sidebar-tickets').on('click', function(e) 
        {
            e.preventDefault();
            $('#adminTabs button[data-bs-target="#support-tickets"]').tab('show');
        });

        $('#export-tickets').on('click', exportTicketsToExcel);

        $('.edit-software').on('click', function() 
        {
            $('#editSoftwareId').val($(this).data('id'));
            $('#editSoftwareName').val($(this).data('name'));
            $('#editSoftwareUrl').val($(this).data('url') || '');
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editSoftwareModal')).show();
        });

        $('.delete-software').on('click', function() 
        {
            const id = $(this).data('id');
            showConfirmationDialog('Delete Software', 'Are you sure you want to delete this software?', 'warning', () => deleteSoftware(id));
        });

        initializeModalEvents();

        function updateTime()
        {
            const now = new Date();
            const options = {
                timeZone: 'Asia/Kolkata',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                weekday: 'long'
            };
            const istTime = now.toLocaleString('en-US', options).replace(',', '');
            $('#current-time').text(`Current Time: ${istTime}`);
        }
        updateTime();
        setInterval(updateTime, 1000);

        $(document).on('click', '.view-messages', function() 
        {
            const id = $(this).data('id');
            loadTicketDetails(id);
        });

        $(document).on('click', '#save-remarks', function() 
        {
            const remarks = $('#ticket-remarks').val();
            if (currentTicketId) 
            {
                apiRequest('/support/tickets/' + currentTicketId, 'PUT', {remarks: remarks}).then((response) => {
                    if (response.success === 200)
                    {
                        toastr.success('Remarks saved successfully');
                    } 
                    else 
                    {
                        toastr.error(response.message || 'Failed to save remarks');
                    }
                }).catch(error => {
                    toastr.error('Failed to save remarks');
                });
            }
        });

        $(document).on('click', '#reopen-ticket', function() 
        {
            if (currentTicketId) 
            {
                showConfirmationDialog('Reopen Ticket', 'Are you sure you want to reopen this ticket?', 'warning', () => {
                    apiRequest('/support/tickets/' + currentTicketId + '/reopen', 'PUT').then((response) => {
                        if (response.success === 200) 
                        {
                            toastr.success('Ticket reopened successfully');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('ticketMessagesModal'));
                            modal.hide();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            loadTickets();
                        } 
                        else 
                        {
                            toastr.error(response.message || 'Failed to reopen ticket');
                        }
                    }).catch(error => {
                        toastr.error('Failed to reopen ticket');
                    });
                });
            }
        });

        $(document).on('click', '#resolve-ticket', function() 
        {
            if (currentTicketId) 
            {
                showConfirmationDialog('Resolve Ticket', 'Are you sure you want to mark this ticket as resolved?', 'info', () => {
                    apiRequest('/support/tickets/' + currentTicketId, 'PUT', {status: 'resolved'}).then((response) => {
                        if (response.success === 200) 
                        {
                            toastr.success('Ticket marked as resolved');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('ticketMessagesModal'));
                            modal.hide();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            loadTickets();
                        } 
                        else 
                        {
                            toastr.error(response.message || 'Failed to resolve ticket');
                        }
                    }).catch(error => {
                        toastr.error('Failed to resolve ticket');
                    });
                });
            }
        });

        $(document).on('click', '#close-ticket', function() 
        {
            if (currentTicketId)
            {
                showConfirmationDialog('Close Ticket', 'Are you sure you want to close this ticket?', 'warning', () => {
                    apiRequest('/support/tickets/' + currentTicketId, 'PUT', {status: 'closed'}).then((response) => {
                        if (response.success === 200) 
                        {
                            toastr.success('Ticket closed successfully');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('ticketMessagesModal'));
                            modal.hide();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            loadTickets();
                        } 
                        else 
                        {
                            toastr.error(response.message || 'Failed to close ticket');
                        }
                    }).catch(error => {
                        toastr.error('Failed to close ticket');
                    });
                });
            }
        });

        $(document).on('hidden.bs.modal', '#ticketMessagesModal', function () 
        {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            currentTicketId = null;
        });

        $(document).on('hidden.bs.modal', '.modal', function () 
        {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        });
    });

    function initializeModalEvents() 
    {
        const addTrialModal = document.getElementById('addTrialModal');
        const editTrialModal = document.getElementById('editTrialModal');
        
        if (addTrialModal) 
        {
            addTrialModal.addEventListener('show.bs.modal', function() 
            {
                loadFeaturesForTrials();
            });
        }
        
        if (editTrialModal)
        {
            editTrialModal.addEventListener('show.bs.modal', function() 
            {
                loadFeaturesForTrials();
            });

            editTrialModal.addEventListener('shown.bs.modal', function() 
            {
                if (currentEditingTrial) 
                {
                    setFeatureValueAfterModalOpen();
                }
            });
        }
    }

    function showConfirmationDialog(title, text, icon, confirmCallback) 
    {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3762b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) confirmCallback();
        });
    }

    function resetTables() 
    {
        $('#features-body').html('<tr><td colspan="6" class="empty-state"><i class="fas fa-star"></i><p>Select a software to view features</p></td></tr>');
        $('#trials-body').html('<tr><td colspan="5" class="empty-state"><i class="fas fa-clock"></i><p>Select a software to view trials</p></td></tr>');
        $('#faqs-body').html('<tr><td colspan="3" class="empty-state"><i class="fas fa-question-circle"></i><p>Select a software to view FAQs</p></td></tr>');
        $('#software-requests-body').html('<tr><td colspan="5" class="empty-state"><i class="fas fa-desktop"></i><p>Select a software to view software requests</p></td></tr>');
        $('#advertisements-body').html('<tr><td colspan="5" class="empty-state"><i class="fas fa-ad"></i><p>Select a software to view advertisements</p></td></tr>');
        $('#tickets-body').html('<tr><td colspan="8" class="empty-state"><i class="fas fa-ticket-alt"></i><p>Select a software to view support tickets</p></td></tr>');
        updateStats({{ $softwares->count() ?? 0 }}, 0, 0, 0, 0, 0);
    }

    async function apiRequest(endpoint, method = 'GET', data = null, includeSoftware = true) 
    {
        if (!currentSoftware && includeSoftware) 
        {
            toastr.error('Please select a software');
            return { success: 400, message: 'No software selected' };
        }
        if (!currentSoftwareUrl && includeSoftware) 
        {
            toastr.error('No software URL configured');
            return { success: 400, message: 'No URL configured' };
        }

        const headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        };
        const options = { method, headers };
        if (data) options.body = JSON.stringify(data);

        let url = `${currentSoftwareUrl.replace(/\/$/, '')}/api${endpoint}`;

        if (includeSoftware && currentSoftware) {
            const separator = endpoint.includes('?') ? '&' : '?';
            url += `${separator}software=${encodeURIComponent(currentSoftware)}`;
        }

        try 
        {
            const response = await fetch(url, options);
            const responseText = await response.text();
            
            let result;
            try 
            {
                result = responseText ? JSON.parse(responseText) : {};
            } 
            catch (e) 
            {
                console.error('Invalid JSON response:', responseText);
                return { success: 500, message: 'Invalid server response' };
            }

            if (!response.ok) 
            {
                console.error(`API Error (${response.status}):`, result.message || responseText);
                toastr.error(result.message || `Error: ${response.status}`);
                return { success: response.status, message: result.message || responseText };
            }
            
            return result;
        } 
        catch (error) 
        {
            console.error('Network error:', error);
            toastr.error('Network error - please check the software URL');
            return { success: 500, message: 'Network error' };
        }
    }

    async function loadAllData() 
    {
        if (!currentSoftware) 
        {
            resetTables();
            return;
        }
        await Promise.all([
            loadFeatures(), 
            loadTrials(), 
            loadFaqs(), 
            loadSoftwareRequests(), 
            loadAdvertisements(),
            loadTickets(),
            loadApkInfo() 
        ]);
    }

    function updateStats(softwaresCount, featuresCount, trialsCount, faqsCount, requestsCount, ticketsCount) 
    {
        $('#softwares-count').text(softwaresCount);
        $('#features-count').text(featuresCount);
        $('#trials-count').text(trialsCount);
        $('#faqs-count').text(faqsCount);
        $('#requests-count').text(requestsCount);
        $('#tickets-count').text(ticketsCount);
    }

    async function loadFeatures() 
    {
        if (!currentSoftware) return;
        const response = await apiRequest('/features');

        if (response.success === 200 && response.data?.length) 
        {
            updateFeaturesTableWithApk(response.data);
            updateStats(
                $('#softwares-count').text(),
                response.data.length,
                $('#trials-count').text(),
                $('#faqs-count').text(),
                $('#requests-count').text(),
                $('#tickets-count').text()
            );
        } 
        else 
        {
            $('#features-body').html('<tr><td colspan="6" class="empty-state"><i class="fas fa-star"></i><p>No features found</p></td></tr>');
            updateStats($('#softwares-count').text(), 0, $('#trials-count').text(), $('#faqs-count').text(), $('#requests-count').text(), $('#tickets-count').text());
        }
    }

    async function createFeature() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software');
            return;
        }

        const form = document.getElementById('addFeatureForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        const metaDescription = data.meta_description?.trim() || '';
        const keyBenefits = $('#meta-key-benefits .meta-key-benefit')
            .map(function() { return $(this).val().trim(); })
            .get()
            .filter(val => val !== '')
            .join('\n');

        const analytics = $('#meta-analytics .meta-analytic')
            .map(function() { return $(this).val().trim(); })
            .get()
            .filter(val => val !== '')
            .join('\n');

        const meta = {};
        if (metaDescription) meta.description = metaDescription;
        if (keyBenefits) meta.key_benefits = keyBenefits;
        if (analytics) meta.analytics = analytics;
        data.meta = Object.keys(meta).length > 0 ? meta : null;
        data.software_name = currentSoftware;

        const response = await apiRequest('/features', 'POST', data);
        if (response.success === 200) 
        {
            toastr.success('Feature added successfully');
            bootstrap.Modal.getInstance(document.getElementById('addFeatureModal')).hide();
            form.reset();
            loadFeatures();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to add feature');
        }
    }

    async function updateFeature() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software');
            return;
        }

        const form = document.getElementById('editFeatureForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        const metaDescription = data.meta_description?.trim() || '';
        const keyBenefits = $('#edit-meta-key-benefits .meta-key-benefit')
            .map(function() { return $(this).val().trim(); })
            .get()
            .filter(val => val !== '')
            .join('\n');

        const analytics = $('#edit-meta-analytics .meta-analytic')
            .map(function() { return $(this).val().trim(); })
            .get()
            .filter(val => val !== '')
            .join('\n');

        const meta = {};
        if (metaDescription) meta.description = metaDescription;
        if (keyBenefits) meta.key_benefits = keyBenefits;
        if (analytics) meta.analytics = analytics;
        data.meta = Object.keys(meta).length > 0 ? meta : null;
        data.software_name = currentSoftware;

        const response = await apiRequest('/features/' + data.id, 'PUT', data);
        if (response.success === 200) 
        {
            toastr.success('Feature updated successfully');
            bootstrap.Modal.getInstance(document.getElementById('editFeatureModal')).hide();
            loadFeatures();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to update feature');
        }
    }

    async function editFeature(id) 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software');
            return;
        }

        const response = await apiRequest(`/features/${id}`);
        if (response.success === 200) 
        {
            const form = document.getElementById('editFeatureForm');
            const feature = response.data;

            form.querySelector('[name="id"]').value = feature.id;
            form.querySelector('[name="feature_name"]').value = feature.feature_name;
            form.querySelector('[name="price"]').value = feature.price;
            form.querySelector('[name="video_url"]').value = feature.video_url || '';
            form.querySelector('[name="status"]').value = feature.status;

            const metaData = feature.meta ? JSON.parse(feature.meta) : {};
            form.querySelector('[name="meta_description"]').value = metaData.description || '';

            const metaKeyBenefitsContainer = $('#edit-meta-key-benefits');
            const metaAnalyticsContainer = $('#edit-meta-analytics');
            metaKeyBenefitsContainer.empty();
            metaAnalyticsContainer.empty();
            const keyBenefits = metaData.key_benefits
                ? metaData.key_benefits.replace(/<br\s*\/?>/gi, '\n').split('\n')
                : [];
            const analytics = metaData.analytics
                ? metaData.analytics.replace(/<br\s*\/?>/gi, '\n').split('\n')
                : [];
            if (keyBenefits.length > 0) 
            {
                keyBenefits.forEach(value => {
                    if (value.trim() !== '') 
                    { 
                        metaKeyBenefitsContainer.append(`
                            <div class="input-group mb-2 meta-input-group">
                                <textarea class="form-control meta-key-benefit">${value}</textarea>
                                <button type="button" class="btn btn-danger remove-meta-input">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `);
                    }
                });
            } 
            if (metaKeyBenefitsContainer.children().length === 0) 
            {
                metaKeyBenefitsContainer.append(`
                    <div class="input-group mb-2 meta-input-group">
                        <textarea class="form-control meta-key-benefit" placeholder="Enter key benefit"></textarea>
                        <button type="button" class="btn btn-danger remove-meta-input">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `);
            }

            if (analytics.length > 0) 
            {
                analytics.forEach(value => {
                    if (value.trim() !== '') 
                    {
                        metaAnalyticsContainer.append(`
                            <div class="input-group mb-2 meta-input-group">
                                <textarea class="form-control meta-analytic">${value}</textarea>
                                <button type="button" class="btn btn-danger remove-meta-input">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `);
                    }
                });
            } 
            if (metaAnalyticsContainer.children().length === 0) 
            {
                metaAnalyticsContainer.append(`
                    <div class="input-group mb-2 meta-input-group">
                        <textarea class="form-control meta-analytic" placeholder="Enter analytic metric"></textarea>
                        <button type="button" class="btn btn-danger remove-meta-input">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `);
            }

            bootstrap.Modal.getOrCreateInstance(document.getElementById('editFeatureModal')).show();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to load feature');
        }
    }

    $(document).on('click', '.remove-meta-input', function() 
    {
        $(this).closest('.input-group').remove();
    });

    function createMetaInput(type, value = '') 
    {
        const placeholder = type === 'key' ? 'Enter key benefit' : 'Enter analytic metric';
        const className = type === 'key' ? 'meta-key-benefit' : 'meta-analytic';
        return `
            <div class="input-group mb-2 meta-input-group">
                <textarea class="form-control ${className}" placeholder="${placeholder}">${value}</textarea>
                <button type="button" class="btn btn-danger remove-meta-input">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
    }

    $('#add-key-benefit').on('click', function() 
    {
        $('#meta-key-benefits').append(createMetaInput('key'));
    });
    $('#add-analytic').on('click', function() 
    {
        $('#meta-analytics').append(createMetaInput('analytic'));
    });

    $('#edit-add-key-benefit').on('click', function() 
    {
        $('#edit-meta-key-benefits').append(createMetaInput('key'));
    });
    $('#edit-add-analytic').on('click', function() 
    {
        $('#edit-meta-analytics').append(createMetaInput('analytic'));
    });
        
    async function deleteFeature(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        showConfirmationDialog('Delete Feature', 'Are you sure you want to delete this feature?', 'warning', async () => {
            const response = await apiRequest(`/features/${id}`, 'DELETE');
            if (response.success === 200) 
            {
                toastr.success('Feature deleted');
                loadFeatures();
            } 
            else 
            {
                toastr.error(response.message || 'Failed to delete feature');
            }
        });
    }

    async function loadTrials() 
    {
        if (!currentSoftware) return;
        const response = await apiRequest('/trials');
        const tbody = $('#trials-body');
        tbody.empty();

        if (response.success === 200 && response.data?.length) 
        {
            let activeTrials = 0;
            const featuresResponse = await apiRequest('/features');
            const featuresMap = {};
            if (featuresResponse.success === 200 && featuresResponse.data) 
            {
                featuresResponse.data.forEach(feature => {
                    featuresMap[feature.id] = feature.feature_name;
                });
            }

            response.data.forEach(trial => {
                const startDate = new Date(trial.start_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                const endDate = new Date(trial.end_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                
                const isActive = trial.status === 'active' && new Date(trial.end_date) >= new Date();
                let statusClass = 'status-expired';
                if (trial.status === 'active') statusClass = 'status-active';
                if (trial.status === 'inactive') statusClass = 'status-inactive';
                if (trial.status === 'cancelled') statusClass = 'status-cancelled';
                
                if (isActive) activeTrials++;
                
                const featureName = featuresMap[trial.feature_id] || 'Unknown Feature';
                
                tbody.append(`
                    <tr>
                        <td>
                            <strong>${trial.client_name}</strong><br>
                            <small>📧 ${trial.email}</small><br>
                            <small>📞 ${trial.phone || 'No phone'}</small>
                        </td>
                        <td>${featureName}</td>
                        <td>
                            <small>Start: ${startDate}</small><br>
                            <small>End: ${endDate}</small>
                        </td>
                        <td><span class="status-badge ${statusClass}">${trial.status}</span></td>
                        <td>
                            <button class="btn btn-xs btn-soft-light" onclick="editTrial(${trial.id})"><i class="fas fa-edit text-warning"></i></button>
                            <button class="btn btn-xs btn-soft-light" onclick="deleteTrial(${trial.id})"> <i class="fas fa-trash text-danger"></i></button>
                        </td>
                    </tr>
                `);
            });
            updateStats($('#softwares-count').text(), $('#features-count').text(), activeTrials, $('#faqs-count').text(), $('#requests-count').text(), $('#tickets-count').text());
        } 
        else
        {
            tbody.html('<tr><td colspan="5" class="empty-state"><i class="fas fa-clock"></i><p>No trials found</p></td></tr>');
            updateStats($('#softwares-count').text(), $('#features-count').text(), 0, $('#faqs-count').text(), $('#requests-count').text(), $('#tickets-count').text());
        }
    }

    async function loadFeaturesForTrials() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }
        
        const response = await apiRequest('/features');
        const editDropdown = document.getElementById('editTrialFeature');
        const addDropdown = document.getElementById('addTrialFeature');
        
        if (editDropdown) 
        {
            editDropdown.innerHTML = '<option value="">Select Feature</option>';
        }
        if (addDropdown) 
        {
            addDropdown.innerHTML = '<option value="">Select Feature</option>';
        }
        
        if (response.success === 200 && response.data?.length) 
        {
            response.data.forEach(feature => {
                if (editDropdown) 
                {
                    const option = document.createElement('option');
                    option.value = feature.id;
                    option.textContent = `${feature.feature_name} - $${Number(feature.price).toFixed(2)}`;
                    editDropdown.appendChild(option);
                }
                if (addDropdown) 
                {
                    const option = document.createElement('option');
                    option.value = feature.id;
                    option.textContent = `${feature.feature_name} - $${Number(feature.price).toFixed(2)}`;
                    addDropdown.appendChild(option);
                }
            });
        } 
        else 
        {
            toastr.warning('No features available. Please add features first.');
        }
    }

    async function createTrial() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software');
            return;
        }
        
        const form = document.getElementById('addTrialForm');
        const data = Object.fromEntries(new FormData(form));
        if (!data.feature_id) 
        {
            toastr.error('Please select a feature');
            return;
        }
        
        data.software_name = currentSoftware;
        
        const response = await apiRequest('/trials', 'POST', data);
        if (response.success === 200) 
        {
            toastr.success('Trial added successfully');
            bootstrap.Modal.getInstance(document.getElementById('addTrialModal')).hide();
            form.reset();
            loadTrials();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to add trial');
        }
    }

    async function updateTrial() 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const form = document.getElementById('editTrialForm');
        const data = Object.fromEntries(new FormData(form));
        const response = await apiRequest(`/trials/${data.id}`, 'PUT', data);
        if (response.success === 200) 
        {
            toastr.success('Trial updated');
            bootstrap.Modal.getInstance(document.getElementById('editTrialModal')).hide();
            loadTrials();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to update trial');
        }
    }

    function formatDateForInput(dateString) 
    {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        
        if (isNaN(date.getTime())) 
        {
            return '';
        }
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    async function editTrial(id) 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software');
            return;
        }

        try 
        {
            const trialResponse = await apiRequest(`/trials/${id}`);
            if (trialResponse.success === 200) 
            {
                currentEditingTrial = trialResponse.data;
                
                await loadFeaturesForTrials();
                
                const form = document.getElementById('editTrialForm');
                
                form.querySelector('[name="id"]').value = currentEditingTrial.id;
                form.querySelector('[name="client_name"]').value = currentEditingTrial.client_name;
                form.querySelector('[name="email"]').value = currentEditingTrial.email;
                form.querySelector('[name="phone"]').value = currentEditingTrial.phone || '';
                
                if (currentEditingTrial.start_date) 
                {
                    form.querySelector('[name="start_date"]').value = formatDateForInput(currentEditingTrial.start_date);
                }
                
                if (currentEditingTrial.end_date) 
                {
                    form.querySelector('[name="end_date"]').value = formatDateForInput(currentEditingTrial.end_date);
                }
                
                form.querySelector('[name="status"]').value = currentEditingTrial.status;
                
                bootstrap.Modal.getOrCreateInstance(document.getElementById('editTrialModal')).show();
                
            } 
            else 
            {
                toastr.error(trialResponse.message || 'Failed to load trial');
            }
        }
        catch (error) 
        {
            toastr.error('Failed to load trial data');
        }
    }

    function setFeatureValueAfterModalOpen() 
    {
        if (!currentEditingTrial) return;
        
        const form = document.getElementById('editTrialForm');
        const featureSelect = form.querySelector('[name="feature_id"]');
        
        if (featureSelect) 
        {
            const attempts = [
                { delay: 100, description: 'First attempt' },
                { delay: 300, description: 'Second attempt' },
                { delay: 500, description: 'Third attempt' },
                { delay: 800, description: 'Final attempt' }
            ];
            
            attempts.forEach((attempt, index) => {
                setTimeout(() => {
                    featureSelect.value = currentEditingTrial.feature_id;
                    if (index === attempts.length - 1 && featureSelect.value != currentEditingTrial.feature_id) 
                    {
                        toastr.warning('Could not set feature automatically. Please select it manually.');
                    }
                }, attempt.delay);
            });
        }
        
        setTimeout(() => {
            currentEditingTrial = null;
        }, 2000);
    }

    async function deleteTrial(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        showConfirmationDialog('Delete Trial', 'Are you sure you want to delete this trial?', 'warning', async () => {
            const response = await apiRequest(`/trials/${id}`, 'DELETE');
            if (response.success === 200) 
            {
                toastr.success('Trial deleted');
                loadTrials();
            }
            else 
            {
                toastr.error(response.message || 'Failed to delete trial');
            }
        });
    }

    async function loadFaqs() 
    {
        if (!currentSoftware) return;
        const response = await apiRequest('/faqs');
        const tbody = $('#faqs-body');
        tbody.empty();
        if (response.success === 200 && response.data?.length)
        {
            response.data.forEach(faq => {
                tbody.append(`
                    <tr>
                        <td>${faq.question}</td>
                        <td>${faq.answer.length > 100 ? faq.answer.substring(0, 100) + '...' : faq.answer}</td>
                        <td>
                            <button class="btn btn-xs btn-soft-light " onclick="editFaq(${faq.id})"><i class="fas fa-edit text-warning"></i></button>
                            <button class="btn btn-xs btn-soft-light " onclick="deleteFaq(${faq.id})"><i class="fas fa-trash text-danger"></i></button>
                        </td>
                    </tr>
                `);
            });
            updateStats($('#softwares-count').text(), $('#features-count').text(), $('#trials-count').text(), response.data.length, $('#requests-count').text(), $('#tickets-count').text());
        } 
        else 
        {
            tbody.html('<tr><td colspan="3" class="empty-state"><i class="fas fa-question-circle"></i><p>No FAQs found</p></td></tr>');
            updateStats($('#softwares-count').text(), $('#features-count').text(), $('#trials-count').text(), 0, $('#requests-count').text(), $('#tickets-count').text());
        }
    }

    async function createFaq() 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const form = document.getElementById('addFaqForm');
        const data = Object.fromEntries(new FormData(form));
        data.software_name = currentSoftware;
        const response = await apiRequest('/faqs', 'POST', data);
        if (response.success === 200)
        {
            toastr.success('FAQ added');
            bootstrap.Modal.getInstance(document.getElementById('addFaqModal')).hide();
            form.reset();
            loadFaqs();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to add FAQ');
        }
    }

    async function updateFaq() 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const form = document.getElementById('editFaqForm');
        const data = Object.fromEntries(new FormData(form));
        const response = await apiRequest(`/faqs/${data.id}`, 'PUT', data);
        if (response.success === 200)
        {
            toastr.success('FAQ updated');
            bootstrap.Modal.getInstance(document.getElementById('editFaqModal')).hide();
            loadFaqs();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to update FAQ');
        }
    }

    async function editFaq(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const response = await apiRequest(`/faqs/${id}`);
        if (response.success === 200) 
        {
            const form = document.getElementById('editFaqForm');
            form.querySelector('[name="id"]').value = response.data.id;
            form.querySelector('[name="question"]').value = response.data.question;
            form.querySelector('[name="answer"]').value = response.data.answer;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editFaqModal')).show();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to load FAQ');
        }
    }

    async function deleteFaq(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        showConfirmationDialog('Delete FAQ', 'Are you sure you want to delete this FAQ?', 'warning', async () => {
            const response = await apiRequest(`/faqs/${id}`, 'DELETE');
            if (response.success === 200) 
            {
                toastr.success('FAQ deleted');
                loadFaqs();
            } 
            else 
            {
                toastr.error(response.message || 'Failed to delete FAQ');
            }
        });
    }

    async function loadSoftwareRequests()
    {
        if (!currentSoftware) return;
        const response = await apiRequest('/software');
        const tbody = $('#software-requests-body');
        tbody.empty();

        if (response.success === 200 && response.data?.length)
        {
            response.data.forEach(request => {
                const requestedDate = request.requested_date ? 
                    new Date(request.requested_date).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) : 'Not specified';
                
                let statusClass = 'status-pending';
                if (request.status === 'approved') statusClass = 'status-active';
                if (request.status === 'rejected') statusClass = 'status-cancelled';
                
                tbody.append(`
                    <tr>
                        <td>
                            <strong>${request.client_name}</strong><br>
                            <small>📧 ${request.email}</small><br>
                            <small>📞 ${request.phone || 'No phone'}</small>
                        </td>
                        <td>${requestedDate}</td>
                        <td>${request.message || 'No message'}</td>
                        <td><span class="status-badge ${statusClass}">${request.status}</span></td>
                        <td>
                            <button class="btn btn-xs btn-soft-light" onclick="editSoftwareRequest(${request.id})"><i class="fas fa-edit text-warning"></i></button>
                            <button class="btn btn-xs btn-soft-light" onclick="deleteSoftwareRequest(${request.id})"><i class="fas fa-trash text-danger"></i></button>
                        </td>
                    </tr>
                `);
            });
            updateStats($('#softwares-count').text(), $('#features-count').text(), $('#trials-count').text(), $('#faqs-count').text(), response.data.length, $('#tickets-count').text());
        } 
        else 
        {
            tbody.html('<tr><td colspan="5" class="empty-state"><i class="fas fa-desktop"></i><p>No software requests found</p></td></tr>');
            updateStats($('#softwares-count').text(), $('#features-count').text(), $('#trials-count').text(), $('#faqs-count').text(), 0, $('#tickets-count').text());
        }
    }

    async function updateSoftwareRequest() 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const form = document.getElementById('editSoftwareRequestForm');
        const data = Object.fromEntries(new FormData(form));
        const response = await apiRequest(`/software/${data.id}`, 'PUT', data);
        if (response.success === 200) 
        {
            toastr.success('Software request updated');
            bootstrap.Modal.getInstance(document.getElementById('editSoftwareRequestModal')).hide();
            loadSoftwareRequests();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to update software request');
        }
    }

    async function editSoftwareRequest(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const response = await apiRequest(`/software/${id}`);
        if (response.success === 200) 
        {
            const form = document.getElementById('editSoftwareRequestForm');
            form.querySelector('[name="id"]').value = response.data.id;
            form.querySelector('[name="client_name"]').value = response.data.client_name;
            form.querySelector('[name="email"]').value = response.data.email;
            form.querySelector('[name="phone"]').value = response.data.phone || '';
            form.querySelector('[name="requested_date"]').value = response.data.requested_date ? 
                formatDateForInput(response.data.requested_date) : '';
            form.querySelector('[name="message"]').value = response.data.message || '';
            form.querySelector('[name="status"]').value = response.data.status;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editSoftwareRequestModal')).show();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to load software request');
        }
    }

    async function deleteSoftwareRequest(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        showConfirmationDialog('Delete Software Request', 'Are you sure you want to delete this software request?', 'warning', async () => {
            const response = await apiRequest(`/software/${id}`, 'DELETE');
            if (response.success === 200) 
            {
                toastr.success('Software request deleted');
                loadSoftwareRequests();
            } 
            else 
            {
                toastr.error(response.message || 'Failed to delete software request');
            }
        });
    }

    async function loadAdvertisements() 
    {
        if (!currentSoftware) return;
        const response = await apiRequest('/advertisements');
        const tbody = $('#advertisements-body');
        tbody.empty();

        if (response.success === 200 && response.data?.length) 
        {
            response.data.forEach(ad => {
                const statusClass = ad.is_active ? 'status-active' : 'status-inactive';
                const statusText = ad.is_active ? 'Active' : 'Inactive';
                
                const startDate = ad.start_date ? 
                    new Date(ad.start_date).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) : 'Not set';
                    
                const endDate = ad.end_date ? 
                    new Date(ad.end_date).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) : 'Not set';

                tbody.append(`
                    <tr>
                        <td>
                            <strong>${ad.title}</strong>
                            ${ad.subtitle ? `<br><small class="text-muted">${ad.subtitle}</small>` : ''}
                        </td>
                        <td>
                            ${ad.badge_text ? `<span class="badge bg-primary">${ad.badge_text}</span>` : 'No badge'}
                        </td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <small>Start: ${startDate}</small><br>
                            <small>End: ${endDate}</small>
                        </td>
                        <td>
                            <button class="btn btn-xs btn-soft-light" onclick="editAdvertisement(${ad.id})"><i class="fas fa-edit text-warning"></i></button>
                            <button class="btn btn-xs btn-soft-light" onclick="deleteAdvertisement(${ad.id})"><i class="fas fa-trash text-danger"></i></button>
                        </td>
                    </tr>
                `);
            });
        } 
        else 
        {
            tbody.html('<tr><td colspan="5" class="empty-state"><i class="fas fa-ad"></i><p>No advertisements found</p></td></tr>');
        }
    }

    function populateSoftwareOptions() 
    {
        const softwareSelect = document.getElementById('editSoftwareSelect');
        if (!softwareSelect) return;
        softwareSelect.innerHTML = '';
        const softwareOptions = $('#softwareSelect option');
        
        softwareOptions.each(function() 
        {
            if ($(this).val()) 
            {
                const option = document.createElement('option');
                option.value = $(this).data('id');
                option.textContent = $(this).val();
                option.setAttribute('data-url', $(this).data('url') || '');
                softwareSelect.appendChild(option);
            }
        });
    }

    async function createAdvertisement() 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const form = document.getElementById('addAdvertisementForm');
        const data = Object.fromEntries(new FormData(form));
        data.is_active = data.is_active ? 1 : 0;
        data.software_name = currentSoftware;
        
        const response = await apiRequest('/advertisements', 'POST', data);
        if (response.success === 200) 
        {
            toastr.success('Advertisement created');
            bootstrap.Modal.getInstance(document.getElementById('addAdvertisementModal')).hide();
            form.reset();
            loadAdvertisements();
        } 
        else if (response.success === 409) 
        {
            toastr.warning('Advertisement already exists. You can only have one advertisement per software.');
            if (response.data) {
                editAdvertisement(response.data.id);
            }
        } 
        else 
        {
            toastr.error(response.message || 'Failed to create advertisement');
        }
    }

    async function updateAdvertisement() 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const form = document.getElementById('editAdvertisementForm');
        const data = Object.fromEntries(new FormData(form));
        data.is_active = data.is_active ? 1 : 0;
        
        const response = await apiRequest(`/advertisements/${data.id}`, 'PUT', data);
        if (response.success === 200) 
        {
            toastr.success('Advertisement updated');
            bootstrap.Modal.getInstance(document.getElementById('editAdvertisementModal')).hide();
            loadAdvertisements();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to update advertisement');
        }
    }

    async function editAdvertisement(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        const response = await apiRequest(`/advertisements/${id}`);
        if (response.success === 200) 
        {
            const form = document.getElementById('editAdvertisementForm');
            const ad = response.data;
            
            form.querySelector('[name="id"]').value = ad.id;
            form.querySelector('[name="title"]').value = ad.title || '';
            form.querySelector('[name="subtitle"]').value = ad.subtitle || '';
            form.querySelector('[name="badge_text"]').value = ad.badge_text || '';
            form.querySelector('[name="media"]').value = ad.media || '';
            form.querySelector('[name="description"]').value = ad.description || '';
            form.querySelector('[name="features"]').value = ad.features || '';
            form.querySelector('[name="pricing"]').value = ad.pricing || '';
            form.querySelector('[name="button"]').value = ad.button || '';
            form.querySelector('[name="footer_note"]').value = ad.footer_note || '';
            form.querySelector('[name="is_active"]').checked = ad.is_active == 1;
            if (ad.start_date) 
            {
                form.querySelector('[name="start_date"]').value = formatDateForInput(ad.start_date);
            }
            if (ad.end_date) 
            {
                form.querySelector('[name="end_date"]').value = formatDateForInput(ad.end_date);
            }
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editAdvertisementModal')).show();
        } 
        else 
        {
            toastr.error(response.message || 'Failed to load advertisement');
        }
    }

    async function deleteAdvertisement(id) 
    {
        if (!currentSoftware) return toastr.error('Please select a software');
        showConfirmationDialog('Delete Advertisement', 'Are you sure you want to delete this advertisement?', 'warning', async () => {
            const response = await apiRequest(`/advertisements/${id}`, 'DELETE');
            if (response.success === 200) 
            {
                toastr.success('Advertisement deleted');
                loadAdvertisements();
            } 
            else 
            {
                toastr.error(response.message || 'Failed to delete advertisement');
            }
        });
    }

    async function loadTickets() 
    {
        if (!currentSoftware) 
        {
            return;
        }

        try 
        {
            const response = await apiRequest('/support/tickets');
            const tbody = $('#tickets-body');
            tbody.empty();

            if (response.success === 200 && response.data && response.data.length) 
            {
                response.data.forEach(ticket => {
                    let statusClass = 'status-pending';
                    if (ticket.status === 'open') statusClass = 'status-open';
                    if (ticket.status === 'in_progress') statusClass = 'status-in-progress';
                    if (ticket.status === 'resolved') statusClass = 'status-resolved';
                    if (ticket.status === 'closed') statusClass = 'status-closed';
                    
                    const createdDate = ticket.created_at ? 
                        new Date(ticket.created_at).toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : 'Unknown';
                    
                    const description = ticket.description ? 
                        (ticket.description.substring(0, 50) + (ticket.description.length > 50 ? '...' : '')) : 
                        'No description';
                    
                    tbody.append(`
                        <tr>
                            <td>${ticket.id}</td>
                            <td>${ticket.ticket_id || 'N/A'}</td>
                            <td>${ticket.subject || 'No Subject'}</td>
                            <td>${description}</td>
                            <td><span class="badge bg-secondary">${ticket.priority || 'Normal'}</span></td>
                            <td><span class="status-badge ${statusClass}">${ticket.status || 'open'}</span></td>
                            <td>${createdDate}</td>
                            <td>
                                <button class="btn btn-xs btn-soft-light view-messages" data-id="${ticket.id}">
                                    <i class="fas fa-eye me-1"></i>View
                                </button>
                            </td>
                        </tr>
                    `);
                });
                updateStats($('#softwares-count').text(), $('#features-count').text(), $('#trials-count').text(), $('#faqs-count').text(), $('#requests-count').text(), response.data.length);
            } 
            else 
            {
                tbody.html('<tr><td colspan="8" class="empty-state"><i class="fas fa-ticket-alt"></i><p>No tickets found</p></td></tr>');
                updateStats($('#softwares-count').text(), $('#features-count').text(), $('#trials-count').text(), $('#faqs-count').text(), $('#requests-count').text(), 0);
            }
        }
        catch (error) 
        {
            console.error('Error loading tickets:', error);
            $('#tickets-body').html('<tr><td colspan="8" class="empty-state"><i class="fas fa-exclamation-triangle"></i><p>Error loading tickets</p></td></tr>');
        }
    }

    async function loadTicketDetails(id) 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }
        try 
        {
            const ticketResponse = await apiRequest(`/support/tickets/${id}`);

            if (ticketResponse.success === 200) 
            {
                const ticket = ticketResponse.data;
                currentTicketId = id;
                $('#ticket-subject').text(ticket.subject || 'No Subject');
                updateTicketStatus(ticket.status);
                $('#ticket-remarks').val(ticket.remarks || '');

                updateTicketActions(ticket.status);

                await displayAttachments(ticket, []);

                const modal = new bootstrap.Modal(document.getElementById('ticketMessagesModal'));
                modal.show();
                
            } 
            else 
            {
                console.error('Failed to load ticket:', ticketResponse);
                const errorMsg = ticketResponse.message || 'Failed to load ticket details';
                toastr.error(errorMsg);
            }
        } 
        catch (error) 
        {
            console.error('Error loading ticket details:', error);
            toastr.error('Failed to load ticket details: ' + error.message);
        }
    }

    function updateTicketStatus(status) 
    {
        const statusElement = $('#ticket-status');
        const statusText = status || 'open';
        let statusClass = 'status-open';
        
        if (statusText === 'open') statusClass = 'status-open';
        else if (statusText === 'in_progress') statusClass = 'status-in-progress';
        else if (statusText === 'resolved') statusClass = 'status-resolved';
        else if (statusText === 'closed') statusClass = 'status-closed';
        else statusClass = 'status-pending';
        
        statusElement.text(statusText).removeClass().addClass(`status-badge ${statusClass}`);
    }

    function updateTicketActions(status) 
    {
        const reopenBtn = $('#reopen-ticket');
        const resolveBtn = $('#resolve-ticket');
        const closeBtn = $('#close-ticket');
        
        reopenBtn.hide();
        resolveBtn.hide();
        closeBtn.hide();
        
        const statusLower = (status || 'open').toLowerCase();
        
        if (statusLower === 'closed' || statusLower === 'resolved') 
        {
            reopenBtn.show();
        } 
        else if (statusLower === 'open' || statusLower === 'in_progress') 
        {
            resolveBtn.show();
            closeBtn.show();
        }
    }

    async function displayAttachments(ticket, messages) 
    {
        const attachmentsContainer = $('#attachments-list');
        const ticketAttachmentsSection = $('#ticket-attachments');
        attachmentsContainer.empty();
        
        let hasAttachments = false;
        
        if (ticket.attachments && Array.isArray(ticket.attachments) && ticket.attachments.length > 0) 
        {
            hasAttachments = true;
            ticket.attachments.forEach(attachment => {
                if (attachment) 
                {
                    const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(attachment);
                    attachmentsContainer.append(`
                        <div class="attachment-item border rounded p-2" style="max-width: 200px;">
                            <div class="text-center">
                                ${isImage ? 
                                    `<img src="${attachment}" class="img-thumbnail mb-2" style="max-width: 100px; max-height: 100px; object-fit: cover;" alt="Attachment" onerror="this.style.display='none'">` :
                                    `<i class="fas fa-file fa-2x text-muted mb-2"></i>`
                                }
                                <div>
                                    <a href="${attachment}" target="_blank" class="btn btn-sm btn-outline-primary mt-1" download>
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    `);
                }
            });
        }
        
        if (hasAttachments) 
        {
            ticketAttachmentsSection.show();
        } 
        else 
        {
            ticketAttachmentsSection.hide();
        }
    }

    function exportTicketsToExcel() 
    {
        if (!currentSoftware) 
        {
            toastr.error('Please select a software first');
            return;
        }

        const table = document.getElementById('tickets-table');
        if (!table) 
        {
            toastr.error('Tickets table not found');
            return;
        }

        try 
        {
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Support Tickets');
            const fileName = `support_tickets_${currentSoftware}_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(wb, fileName);
            toastr.success('Tickets exported successfully');
        } 
        catch (error) 
        {
            toastr.error('Failed to export tickets');
        }
    }

    async function updateSoftware() 
    {
        const form = document.getElementById('editSoftwareForm');
        const id = $('#editSoftwareId').val();
        const formData = new FormData(form);
        // console.log(formData);
        try 
        {
            const response = await fetch(`/software/${id}`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken, 
                    'Accept': 'application/json' 
                },
                body: formData
            });
            const result = await response.json();
            if (response.ok && result.success === 200) 
            {
                toastr.success('Software updated');
                bootstrap.Modal.getInstance(document.getElementById('editSoftwareModal')).hide();
                location.reload();
            } 
            else 
            {
                toastr.error(result.message || 'Failed to update software');
            }
        } 
        catch (error) 
        {
            toastr.error('Failed to update software');
        }
    }

    async function deleteSoftware(id) 
    {
        try 
        {
            const response = await fetch(`/software/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const result = await response.json();
            if (response.ok && result.success === 200) 
            {
                toastr.success('Software deleted');
                location.reload();
            } 
            else 
            {
                toastr.error(result.message || 'Failed to delete software');
            }
        } 
        catch (error) 
        {
            toastr.error('Failed to delete software');
        }
    }

    setInterval(() => {
    if (currentSoftware && !isLoading) 
    {
        $('#progressLoader').fadeIn(200);
        loadAllData().finally(() => {
            setTimeout(() => {
                $('#progressLoader').fadeOut(200);
            }, 500);
        });
        }
    }, 30000);
</script>
</body>
</html>