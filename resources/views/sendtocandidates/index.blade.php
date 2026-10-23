@extends('layouts.app')

@section('title', 'Mail Send to Candidate')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">

<style>
    #sendtoother-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        font-weight: bold !important;
    }

    /* Sticky Header */
    #sendtoother-listing thead th {
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .table thead th,
    .jsgrid .jsgrid-table thead th {
        font-weight: 600 !important;
    }

    .btn-group > .btn.active {
        color:black;
    }
    .table > :not(caption) {
        padding: 0 !important;
    }
    .dt-buttons .btn {
        background: #004085 !important;
        color: white !important;
        border-radius: 4px;
        padding: 5px 15px;
        font-size: 13px;
        margin-right: 5px;
        border: 1px solid #003366;
    }
    .dt-buttons .btn:hover {
        background: #002752 !important;
    }
    
    /* Share button styling */
    .btn-share {
        background: linear-gradient(45deg, #25D366, #128C7E) !important;
        border: none !important;
        color: white !important;
    }
    .btn-share:hover {
        background: linear-gradient(45deg, #1da851, #0c6d5f) !important;
    }
    
    /* File upload styling */
    .file-upload-area {
        border: 2px dashed #ccc;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .file-upload-area:hover {
        border-color: #004085;
        background-color: #e9ecef;
    }
    
    .file-upload-area.dragover {
        border-color: #28a745;
        background-color: #d4edda;
    }
    
    .selected-file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        margin-bottom: 8px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }
    
    .selected-file-item:hover {
        background-color: #e9ecef;
    }
    
    .file-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .file-icon {
        font-size: 20px;
        color: #004085;
    }
    
    .file-details {
        flex-grow: 1;
    }
    
    .file-name {
        font-weight: 500;
        margin-bottom: 2px;
    }
    
    .file-size {
        font-size: 12px;
        color: #6c757d;
    }
    
    .remove-file-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
    }
    
    .remove-file-btn:hover {
        background-color: #f8d7da;
    }
    
    /* Attachment types styling */
    .attachment-type-badge {
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: 8px;
    }
    
    .attachment-pdf {
        background-color: #e74c3c;
        color: white;
    }
    
    .attachment-doc {
        background-color: #2980b9;
        color: white;
    }
    
    .attachment-image {
        background-color: #27ae60;
        color: white;
    }
    
    .attachment-other {
        background-color: #7f8c8d;
        color: white;
    }

    /* Candidate files section styles */
    .candidate-files-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        border: 1px solid #dee2e6;
    }

    .candidate-files-section h6 {
        color: #004085;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .file-checkbox-item {
        padding: 8px 12px;
        margin-bottom: 5px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        transition: all 0.2s;
    }

    .file-checkbox-item:hover {
        background: #e9ecef;
    }

    .file-checkbox-item .form-check {
        margin: 0;
        display: flex;
        align-items: center;
    }

    .file-checkbox-item .form-check-input {
        margin-right: 10px;
        cursor: pointer;
    }

    .file-checkbox-item .form-check-label {
        flex: 1;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-meta {
        font-size: 12px;
        color: #6c757d;
    }

    .files-loading {
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }
</style>

<div class="content-wrapper">

    {{-- Success Message --}}
    <div class="row mb-3">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="mdi mdi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1">Send To Candidate</h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Send To Candidate</li>
            </ol>
        </div>

        <button type="button" class="btn btn-sm send-candidates-btn shadow-sm send-to-recruiter-btn me-2">
            <i class="mdi mdi-send"></i> Send Mail
        </button>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title mb-3 fw-semibold">Mail Send to Candidate - List</h4>
            <div class="table-responsive">
                <table id="sendtoother-listing" class="table table-hover align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>S.No</th>
                            <th>To Mail</th>
                            <th>Subject</th>
                            <th>Sent By</th>
                            <th>Sent on</th>
                            <!--<th>Attachments</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brochures as $key => $brochure)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-semibold">{{ $brochure->to_email }}</td>
                                <td class="fw-semibold">{{ $brochure->subject }}</td>
                                <td>{{ $users[$brochure->created_by] ?? '' }}</td>
                                <td>{{ $brochure->created_at->format('d M, Y h:i A') }}</td>
                                <!--<td>
                                    @php
                                        $attachments = json_decode($brochure->attachments, true);
                                    @endphp
                                    @if(is_array($attachments) && count($attachments) > 0)
                                        <span class="badge bg-info">
                                            {{ count($attachments) }} file(s)
                                        </span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>-->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Send Mail Modal -->
<div class="modal fade" id="sendCandidatesModal" tabindex="-1" aria-labelledby="sendCandidatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendCandidatesModalLabel">Send Mail to Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendCandidatesForm" action="{{ route('sendtocandidates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">To mail <span class="text-danger">*</span></label>
                            <select name="to_mail" class="form-select" id="to_email" required>
                                <option value="">Please Select Candidate</option>
                                @foreach($candidates as $candidate)
                                    <option value="{{ $candidate->email }}" data-candidate-id="{{ $candidate->id }}">
                                        {{ $candidate->candidate_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cc_mail" class="form-label">CC Mail <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cc_mail" name="cc_mail" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_subject" class="form-label">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_subject" name="subject" 
                                   value="Marketing Brochures" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_message" class="form-label">Email Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="modal_message" name="message" rows="6" required style="font-family: Arial, sans-serif; line-height: 1.6;">
Dear Sir/Madam,

Please find attached our latest brochures for your reference.

Best regards,
Marketing Team
                        </textarea>
                    </div>

                    <!-- Candidate's Existing Files Section -->
                    <div class="candidate-files-section" id="candidateFilesSection" style="display: none;">
                        <h6><i class="mdi mdi-file-document-outline me-2"></i>Candidate's Existing Files</h6>
                        <div id="candidateFilesLoading" class="files-loading" style="display: none;">
                            <i class="mdi mdi-loading mdi-spin"></i> Loading files...
                        </div>
                        <div id="candidateFilesList" class="mt-2">
                            <!-- Files will be loaded here dynamically -->
                        </div>
                        <small class="text-muted">Check the files you want to attach with this email.</small>
                    </div>

                    <!-- File Attachments Section (Upload New Files) -->
                    <div class="mb-3">
                        <label class="form-label">Upload New Attachments</label>
                        <div class="file-upload-area" id="fileUploadArea">
                            <i class="mdi mdi-cloud-upload mdi-36px text-muted mb-2"></i>
                            <p class="mb-1">Click to browse or drag & drop files here</p>
                            <p class="text-muted small mb-0">Max file size: 10MB each. Allowed: PDF, DOC, DOCX, JPG, PNG, TXT, XLS, XLSX</p>
                            <input type="file" name="attachments[]" id="fileInput" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt,.xls,.xlsx" style="display: none;">
                        </div>
                        <div id="selectedFilesContainer" class="mt-3" style="display: none;">
                            <h6>Selected Files:</h6>
                            <div id="selectedFilesList" class="mt-2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="mdi mdi-send"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Share List Modal -->
<div class="modal fade" id="shareListModal" tabindex="-1" aria-labelledby="shareListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareListModalLabel">Share Mail History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the mail history via:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="shareWhatsAppBtn">
                        <i class="mdi mdi-whatsapp me-2"></i> WhatsApp
                    </button>
                    <button type="button" class="btn btn-danger btn-lg" id="shareEmailBtn">
                        <i class="mdi mdi-email me-2"></i> Email
                    </button>
                </div>
                
                <div class="mt-4">
                    <label class="form-label">Or download data in:</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="downloadExcelBtn">
                            <i class="mdi mdi-file-excel"></i> Excel
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="downloadPDFBtn">
                            <i class="mdi mdi-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<!-- Export Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        if ($.fn.dataTable.isDataTable('#sendtoother-listing')) {
            $('#sendtoother-listing').DataTable().destroy();
        }

        const table = $('#sendtoother-listing').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                "<'row'<'col-sm-12'B>>",
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-excel"></i> Excel',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                    title: 'RMS_Mail_History_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                    title: 'RMS_Mail_History_List_' + new Date().toLocaleDateString('en-GB'),
                    customize: function(doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.styles.tableHeader = {
                            fillColor: '#004085',
                            color: '#ffffff',
                            bold: true
                        };
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-printer"></i> Print',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                    title: '<h3 class="text-center mb-4">Mail History List</h3>',
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('table table-bordered').css('font-size', '12px');
                        $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                        $(win.document.body).find('h1').css('text-align','center');
                        $(win.document.body).find('h1').css('color', '#004085');
                    }
                },
                {
                    text: '<i class="mdi mdi-share-variant"></i> Share',
                    className: 'btn btn-sm btn-share',
                    action: function (e, dt, node, config) {
                        $('#shareListModal').modal('show');
                    }
                }
            ],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search mail history...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: '<i class="mdi mdi-chevron-right"></i>',
                    previous: '<i class="mdi mdi-chevron-left"></i>'
                }
            },
            responsive: true,
            autoWidth: false
        });

        // Candidate selection change – load their files
        $('#to_email').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const candidateId = selectedOption.data('candidate-id');
            
            if (candidateId) {
                loadCandidateFiles(candidateId);
            } else {
                $('#candidateFilesSection').hide();
                $('#candidateFilesList').empty();
            }
        });

        function loadCandidateFiles(candidateId) {
            $('#candidateFilesSection').show();
            $('#candidateFilesLoading').show();
            $('#candidateFilesList').empty();

            $.ajax({
                url: '{{ route("candidates.files") }}',
                method: 'POST',
                data: {
                    candidate_id: candidateId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    renderCandidateFiles(response.files);
                },
                error: function(xhr) {
                    console.error('Error loading files:', xhr);
                    $('#candidateFilesList').html('<p class="text-danger">Failed to load files. Please try again.</p>');
                },
                complete: function() {
                    $('#candidateFilesLoading').hide();
                }
            });
        }

        function renderCandidateFiles(files) {
            const container = $('#candidateFilesList');
            container.empty();

            if (!files || files.length === 0) {
                container.html('<p class="text-muted">No existing files found for this candidate.</p>');
                return;
            }

            let html = '';
            files.forEach(file => {
                // Determine icon based on file type
                const ext = file.file_name.split('.').pop().toLowerCase();
                let icon = 'mdi-file-document-outline';
                if (ext === 'pdf') icon = 'mdi-file-pdf';
                else if (ext === 'doc' || ext === 'docx') icon = 'mdi-file-word';
                else if (ext === 'jpg' || ext === 'jpeg' || ext === 'png') icon = 'mdi-file-image';
                
                html += `
                    <div class="file-checkbox-item">
                        <div class="form-check">
                            <input class="form-check-input candidate-file-checkbox" type="checkbox" 
                                   name="candidate_file_ids[]" value="${file.id}" id="file_${file.id}" checked>
                            <label class="form-check-label" for="file_${file.id}">
                                <span>
                                    <i class="mdi ${icon} me-2"></i>
                                    ${file.file_title || file.file_name}
                                </span>
                                <span class="file-meta">
                                    <i class="mdi mdi-calendar"></i> ${file.uploaded_date}
                                </span>
                            </label>
                        </div>
                    </div>
                `;
            });
            container.html(html);
        }

        // File Upload Handling
        const fileInput = document.getElementById('fileInput');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const selectedFilesContainer = document.getElementById('selectedFilesContainer');
        const selectedFilesList = document.getElementById('selectedFilesList');
        let selectedFiles = [];

        // Click on upload area
        fileUploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileUploadArea.classList.add('dragover');
        }

        function unhighlight() {
            fileUploadArea.classList.remove('dragover');
        }

        fileUploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // File input change
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File "${file.name}" exceeds 10MB limit.`);
                    continue;
                }
                
                // Validate file type
                const allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt', 'xls', 'xlsx'];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                
                if (!allowedTypes.includes(fileExtension)) {
                    alert(`File type "${fileExtension}" is not allowed.`);
                    continue;
                }
                
                selectedFiles.push(file);
            }
            
            updateSelectedFilesList();
        }

        function updateSelectedFilesList() {
            selectedFilesList.innerHTML = '';
            
            if (selectedFiles.length > 0) {
                selectedFilesContainer.style.display = 'block';
                
                selectedFiles.forEach((file, index) => {
                    const fileSize = (file.size / (1024*1024)).toFixed(2);
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    
                    // Determine file type badge
                    let badgeClass = 'attachment-other';
                    if (['pdf'].includes(fileExtension)) badgeClass = 'attachment-pdf';
                    else if (['doc', 'docx'].includes(fileExtension)) badgeClass = 'attachment-doc';
                    else if (['jpg', 'jpeg', 'png'].includes(fileExtension)) badgeClass = 'attachment-image';
                    
                    const fileItem = document.createElement('div');
                    fileItem.className = 'selected-file-item';
                    fileItem.innerHTML = `
                        <div class="file-info">
                            <i class="mdi mdi-file-document-outline file-icon"></i>
                            <div class="file-details">
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${fileSize} MB</div>
                            </div>
                            <span class="attachment-type-badge ${badgeClass}">${fileExtension.toUpperCase()}</span>
                        </div>
                        <button type="button" class="remove-file-btn" data-index="${index}">
                            <i class="mdi mdi-close"></i>
                        </button>
                    `;
                    selectedFilesList.appendChild(fileItem);
                });
            } else {
                selectedFilesContainer.style.display = 'none';
            }
        }

        // Remove file
        $(document).on('click', '.remove-file-btn', function() {
            const index = $(this).data('index');
            selectedFiles.splice(index, 1);
            updateSelectedFilesList();
        });

        // Send Mail Button
        $('.send-to-recruiter-btn').click(function() {
            $('#sendCandidatesModal').modal('show');
            // Reset form
            selectedFiles = [];
            updateSelectedFilesList();
        });

        // Form submission
        $('#sendCandidatesForm').submit(function(e) {
            e.preventDefault();
            
            // Check if at least one attachment type is selected
            const hasUploadedFiles = selectedFiles.length > 0;
            const hasCandidateFiles = $('.candidate-file-checkbox:checked').length > 0;
            
            if (!hasUploadedFiles && !hasCandidateFiles) {
                alert('Please select at least one attachment (upload file or candidate file).');
                return false;
            }
            
            // Create FormData
            const formData = new FormData(this);
            
            // Append uploaded files
            selectedFiles.forEach((file) => {
                formData.append('attachments[]', file);
            });
            
            // Show loading
            const submitBtn = $('#submitBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Sending...');
            
            // AJAX request
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#sendCandidatesModal').modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        alert(response.message);
                    }
                    submitBtn.prop('disabled', false).html(originalText);
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    alert(errorMsg);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Share functionality (your existing code) – you can keep it here
        // ...
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@endsection