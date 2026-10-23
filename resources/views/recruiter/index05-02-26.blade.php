@extends('layouts.app')

@section('title', 'Recruiters')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    #order-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
    }
    #order-listing thead th {
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .selected-candidates-info {
        background: #e7f3ff;
        border-left: 4px solid #004085;
        border-radius: 8px;
    }
    .send-candidates-btn {
        background: linear-gradient(45deg, #ff9800, #ff5722);
        border: none;
        color: white;
    }
    .send-candidates-btn:hover {
        background: linear-gradient(45deg, #f57c00, #e64a19);
        color: white;
    }
    .candidate-detail-item {
        padding: 8px;
        border-bottom: 1px solid #e0e0e0;
        border-radius: 5px;
        margin-bottom: 5px;
        background: #f9f9f9;
    }
    .candidate-detail-item:last-child {
        border-bottom: none;
    }
    .resume-badge {
        font-size: 11px;
        padding: 3px 8px;
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
    .tox-notifications-container{
        display:none !important;
    }
    .tox-tinymce-aux{
        display:none !important;
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Selected Candidates Info --}}
    @if(request('from_candidates'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info selected-candidates-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-account-multiple-check"></i>
                        <strong>Selected Candidates Ready to Send</strong>
                        <span id="selectedCandidatesCount" class="badge bg-primary ms-2">0</span>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline"></i>
                                Select a recruiter below to send the candidate profiles
                            </small>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm send-candidates-btn shadow-sm send-to-recruiter-btn me-2" >
                            <i class="mdi mdi-send"></i> Send Candidates
                        </button>
                        <a href="{{ route('candidates.index') }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="mdi mdi-arrow-left"></i> Change Selection
                        </a>
                        <button type="button" id="clearSelectionBtn" class="btn btn-sm btn-outline-danger">
                            <i class="mdi mdi-close"></i> Clear All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1">Recruiters</h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                @if(request('from_candidates'))
                <li class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Candidates</a></li>
                @endif
                <li class="breadcrumb-item active">Recruiters</li>
            </ol>
        </div>

        <div>
            @if(request('from_candidates'))
            <a href="{{ route('candidates.index') }}" class="btn btn-info btn-sm shadow-sm me-2">
                <i class="mdi mdi-arrow-left"></i> Back to Candidates
            </a>
            @endif
            <a href="{{ route('add-user') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="mdi mdi-plus"></i> Add Recruiter
            </a>
        </div>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            <h4 class="card-title mb-3 fw-semibold">Recruiters List</h4>
            
            @if(request('from_candidates'))
            <p class="text-muted mb-3">
                <i class="mdi mdi-send-circle text-warning"></i>
                Click "Send Candidates" to send selected candidate profiles to any recruiter
            </p>
            @else
            <p class="text-muted mb-3">Manage your recruiters list</p>
            @endif

            <div class="table-responsive">
                <table id="order-listing" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Emp Code</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Contact</th>
                            <th>DOJ</th>
                            <th>DOL</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($users as $key => $record)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $record->company_serial_number }}</td>
                                <td class="fw-medium">{{ $record->name }}</td>
                                <td>{{ $record->designation }}</td>
                                <td>{{ $record->staff_contact_no }}</td>
                                <td>{{ $record->date_of_joining }}</td>
                                <td>{{ $record->date_of_living }}</td>
                                <td>{{ $record->user_type }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'Active' => 'success',
                                            'Inactive' => 'danger',
                                            'Disable' => 'warning'
                                        ][$record->status] ?? 'secondary';
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $record->status }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if(request('from_candidates'))
                                    <!--<button type="button" class="btn btn-sm send-candidates-btn shadow-sm send-to-recruiter-btn me-2" 
                                            data-recruiter-id="{{ $record->id }}"
                                            data-recruiter-name="{{ $record->name }}"
                                            data-recruiter-email="{{ $record->email }}">
                                        <i class="mdi mdi-send"></i> Send Candidates
                                    </button>-->
                                    @endif

                                    <a href="{{ route('recruiter.show', $record->id) }}" 
                                       class="btn btn-sm btn-success text-white me-1 shadow-sm">
                                        <i class="mdi mdi-eye"></i> View
                                    </a>

                                    <a href="{{ route('recruiters.edit', $record->id) }}" 
                                       class="btn btn-sm btn-primary text-white shadow-sm">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<!-- Send Candidates Modal -->
<div class="modal fade" id="sendCandidatesModal" tabindex="-1" aria-labelledby="sendCandidatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendCandidatesModalLabel">Send Candidates to Recruiter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendCandidatesForm" action="{{ route('candidates.send-to-recruiter') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Recruiter Type</label>
                            <select name="rec_type" id="rec_type" class="form-select select2-basic">
                            <option value="">Select the type</option>
                            <option value="candidate">Candidate</option>
                            <option value="client">Client</option>
                            <option value="agent">Agent</option>
                            <option value="service provider">Service Provider</option>
                            </select>
                            <!--<input type="text" class="form-control" id="modal_recruiter_name" readonly>-->
                            <input type="hidden" name="recruiter_id" id="modal_recruiter_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Send to *</label>
                            <select name="send_to" id="send_to" class="form-select select2-basic" required disabled>
                                <option value="">Select the type first</option>
                            </select>
                            <div id="send_to_loading" class="mt-2 d-none">
                                <small class="text-muted">
                                    <i class="mdi mdi-loading mdi-spin"></i> Loading...
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_subject" class="form-label">Email Subject *</label>
                            <input type="text" class="form-control" id="modal_subject" name="subject" 
                                   value="Candidate Profiles for Review - [count] Candidates" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_message" class="form-label">Email Content *</label>
                        <textarea class="form-control" id="modal_message" name="message" rows="10" required style="font-family: Arial, sans-serif; line-height: 1.6;"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Selected Candidates</label>
                        <div id="modal_candidates_list" class="border p-3 bg-light rounded" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-center text-muted">
                                <i class="mdi mdi-loading mdi-spin"></i> Loading candidates...
                            </div>
                        </div>
                        <div id="resume_summary" class="mt-2 p-2 bg-info text-white rounded d-none">
                            <i class="mdi mdi-file-document"></i>
                            <span id="resume_count">0</span> resumes will be attached automatically
                        </div>
                    </div>

                    <input type="hidden" name="candidate_ids" id="modal_candidate_ids">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-send"></i> Send Email with Resumes
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
                <h5 class="modal-title" id="shareListModalLabel">Share Recruiters List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the recruiters list via:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="shareWhatsAppBtn">
                        <i class="mdi mdi-whatsapp me-2"></i> WhatsApp
                    </button>
                    <button type="button" class="btn btn-danger btn-lg" id="shareEmailBtn">
                        <i class="mdi mdi-email me-2"></i> Email
                    </button>
                    <!--<button type="button" class="btn btn-primary btn-lg" id="shareSMSBtn">
                        <i class="mdi mdi-message me-2"></i> SMS
                    </button>-->
                </div>
                
                <div class="mt-4">
                    <label class="form-label">Or download data in:</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="downloadExcelBtn">
                            <i class="mdi mdi-file-excel"></i> Excel
                        </button>
                        <!--<button type="button" class="btn btn-outline-secondary" id="downloadCSVBtn">
                            <i class="mdi mdi-file-delimited"></i> CSV
                        </button>-->
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Add this in your head section -->
<script src="https://cdn.tiny.cloud/1/YOUR-API-KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    
    $(document).ready(function() {
        function initializeSelect2() {
            $('.select2-basic').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true,
                dropdownParent: $('#sendCandidatesModal') // Important for modal
            });
        }

        // Initialize Select2 on page load
        initializeSelect2();
        $('#sendCandidatesModal').on('shown.bs.modal', function() {
            // Destroy existing Select2 instances
            $('#rec_type').select2('destroy');
            $('#send_to').select2('destroy');
            
            // Re-initialize with proper modal context
            $('#rec_type').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Select recipient type',
                allowClear: true,
                dropdownParent: $('#sendCandidatesModal')
            });
            
            $('#send_to').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Select recipient',
                allowClear: true,
                disabled: true,
                dropdownParent: $('#sendCandidatesModal')
            });
            
            // Update email subject with count
            const count = selectedCandidates.length;
            $('#modal_subject').val(`Candidate Profiles for Review - ${count} Candidates`);
        });

        $('#sendCandidatesModal').on('hidden.bs.modal', function() {
            // Reset the form
            $('#rec_type').val('').trigger('change');
            $('#send_to').val('').trigger('change');
            $('#send_to').prop('disabled', true);
            $('#send_to_loading').addClass('d-none');
        });
        if ($.fn.dataTable.isDataTable('#order-listing')) {
            $('#order-listing').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#order-listing').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                 "<'row'<'col-sm-12'B>>",
            buttons: [
                {
                    extend: 'csv',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-delimited"></i> CSV',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    title: 'Recruiters_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-excel"></i> Excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    title: 'Recruiters_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    title: 'Recruiters_List_' + new Date().toLocaleDateString('en-GB'),
                    customize: function(doc) {
                        doc.content[1].table.widths = 
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
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
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    title: '<h3 class="text-center mb-4">Recruiters List</h3>',
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
                searchPlaceholder: "Search recruiters...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: '<i class="mdi mdi-chevron-right"></i>',
                    previous: '<i class="mdi mdi-chevron-left"></i>'
                }
            },
            responsive: true,
            autoWidth: false
        });

        let selectedCandidates = [];
        let candidateDetails = {};

         // Load selected candidates from session storage
        function loadSelectedCandidates() {
            const storedCandidates = sessionStorage.getItem('selectedCandidates');
            const storedDetails = sessionStorage.getItem('candidateDetails');
            
            console.log("Stored Candidates:", storedCandidates);
            console.log("Stored Details:", storedDetails);
            
            if (storedCandidates) {
                selectedCandidates = JSON.parse(storedCandidates);
                if (storedDetails) {
                    candidateDetails = JSON.parse(storedDetails);
                }
                updateSelectedCandidatesDisplay();
                
                // Debug: Log candidate details
                console.log("Loaded Candidate Details:", candidateDetails);
                console.log("Total Candidates:", selectedCandidates.length);
            } else {
                @if(request('from_candidates'))
                alert('No candidates selected. Redirecting to candidates page.');
                window.location.href = "{{ route('candidates.index') }}";
                @endif
            }
        }

        // Update selected candidates display
        function updateSelectedCandidatesDisplay() {
            $('#selectedCandidatesCount').text(selectedCandidates.length);
        }

        // Send to recruiter button click
        $('.send-to-recruiter-btn').click(function() {
            const recruiterId = $(this).data('recruiter-id');
            const recruiterName = $(this).data('recruiter-name');
            const recruiterEmail = $(this).data('recruiter-email');

            if (selectedCandidates.length === 0) {
                alert('No candidates selected. Please go back to candidates page and select candidates.');
                return;
            }

            // Set modal values
            $('#modal_recruiter_id').val(recruiterId);
            $('#modal_recruiter_name').val(recruiterName + ' - ' + recruiterEmail);
            $('#modal_candidate_ids').val(selectedCandidates.join(','));
            
            // Update email subject with count
            $('#modal_subject').val(`Candidate Profiles for Review - ${selectedCandidates.length} Candidates`);

            // Generate email content with candidate details
            generateEmailContent(recruiterName);

            // Show selected candidates details in modal
            displayCandidateDetails();

            $('#sendCandidatesModal').modal('show');
        });

        function generateEmailContent(recruiterName) {
            let emailContent = `Dear ${recruiterName},<br><br>`;
            emailContent += `Please find the candidate profiles below for your review. A total of ${selectedCandidates.length} candidates have been shortlisted.<br><br>`;

            if (selectedCandidates.length > 0) {
                emailContent += `<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Industry/Sector</th>
                            <th>Experience</th>
                            <th>Qualification</th>
                            <th>Location</th>
                            <th>CTC</th>
                            <th>Mobile</th>
                            <th>Resume</th>
                        </tr>
                    </thead>
                    <tbody>`;
                
                selectedCandidates.forEach((candidateId, index) => {
                    const candidate = candidateDetails[candidateId];
                    if (candidate) {
                        emailContent += `<tr>
                            <td>${index + 1}</td>
                            <td>${candidate.name || 'N/A'}</td>
                            <td>${candidate.designation || 'N/A'}</td>
                            <td>${candidate.industry || ''} / ${candidate.sector || 'N/A'}</td>
                            <td>${candidate.experience || 'N/A'}</td>
                            <td>${candidate.qualification || 'N/A'}</td>
                            <td>${candidate.location || 'N/A'}</td>
                            <td>${candidate.ctc || 'N/A'}</td>
                            <td>${candidate.mobile || 'N/A'}</td>
                            <td style="text-align: center;">${candidate.has_resume == '1' ? '✓' : '✗'}</td>
                        </tr>`;
                    }
                });
                
                emailContent += `</tbody></table><br>`;
            }

            emailContent += `All available resumes are attached with this email for your detailed review.<br><br>`;
            emailContent += `Best regards,<br>`;
            emailContent += `${'{{ auth()->user()->name }}'}<br>`;
            emailContent += `Recruitment Team`;

            tinymce.get('modal_message').setContent(emailContent);
        }
        tinymce.init({
            selector: '#modal_message',
            height: 300,
            menubar: false,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | table | link',
            content_style: 'body { font-family:Arial,sans-serif; font-size:14px }'
        });
        // Display ALL candidate details in modal
        function displayCandidateDetails() {
            let html = '';
            let resumeCount = 0;
            
            if (Object.keys(candidateDetails).length > 0) {
                html += '<div class="candidate-details">';
                selectedCandidates.forEach((candidateId, index) => {
                    const candidate = candidateDetails[candidateId];
                    if (candidate) {
                        const hasResume = candidate.has_resume == '1';
                        if (hasResume) resumeCount++;
                        
                        html += `
                            <div class="candidate-detail-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="text-primary fs-5">${index + 1}. ${candidate.name || 'N/A'}</strong>
                                        <div class="small text-muted mt-1">CV Code: ${candidate.cvcode || 'N/A'}</div>
                                    </div>
                                    <div>
                                        ${hasResume ? 
                                            `<div class="btn-group btn-group-sm">
                                                <a href="${candidate.resume}" 
                                                target="_blank" 
                                                class="btn btn-success"
                                                title="Download Resume">
                                                    <i class="mdi mdi-download"></i> Download
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-info view-resume-btn"
                                                        data-resume-url="${candidate.resume}"
                                                        data-candidate-name="${candidate.name}"
                                                        data-toggle="tooltip"
                                                        title="View Resume">
                                                    <i class="mdi mdi-eye"></i> View
                                                </button>
                                            </div>` 
                                            : 
                                            `<span class="badge bg-warning">
                                                <i class="mdi mdi-file-remove"></i> No Resume
                                            </span>`
                                        }
                                    </div>
                                </div>
                                <div class="row small text-muted mt-2">
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <i class="mdi mdi-briefcase me-1"></i>
                                            <strong>Designation:</strong> ${candidate.designation}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-factory me-1"></i>
                                            <strong>Industry:</strong> ${candidate.industry}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-chart-timeline me-1"></i>
                                            <strong>Experience:</strong> ${candidate.experience}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <i class="mdi mdi-school me-1"></i>
                                            <strong>Qualification:</strong> ${candidate.qualification}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-account me-1"></i>
                                            <strong>Age:</strong> ${candidate.age}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-map-marker me-1"></i>
                                            <strong>Location:</strong> ${candidate.location}
                                        </div>
                                    </div>
                                </div>
                                <div class="row small text-muted mt-2 border-top pt-2">
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <i class="mdi mdi-cash me-1"></i>
                                            <strong>Current CTC:</strong> ${candidate.ctc}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <i class="mdi mdi-calendar-clock me-1"></i>
                                            <strong>Notice Period:</strong> ${candidate.notice_period}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <i class="mdi mdi-phone me-1"></i>
                                            <strong>Mobile:</strong> 
                                            <a href="tel:${candidate.mobile}" class="text-decoration-none">${candidate.mobile}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="candidate-detail-item mb-3 p-3 border rounded bg-light">
                                <strong>${index + 1}. Candidate ID: ${candidateId}</strong>
                                <div class="text-danger small">
                                    <i class="mdi mdi-alert"></i> Details not available
                                </div>
                            </div>
                        `;
                    }
                });
                html += '</div>';
                
                // Show resume summary
                if (resumeCount > 0) {
                    $('#resume_summary').removeClass('d-none');
                    $('#resume_count').text(resumeCount);
                } else {
                    $('#resume_summary').addClass('d-none');
                }
            } else {
                html = `
                    <div class="text-center py-4">
                        <i class="mdi mdi-account-multiple text-primary" style="font-size: 48px;"></i>
                        <h5 class="mt-2">${selectedCandidates.length} Candidates Selected</h5>
                        <p class="text-muted">Candidate details could not be loaded.</p>
                        <small class="text-danger">
                            <i class="mdi mdi-alert-circle"></i> 
                            Please go back to candidates page and reselect candidates.
                        </small>
                    </div>
                `;
            }

            $('#modal_candidates_list').html(html);
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Add event listeners for view resume buttons
            $('.view-resume-btn').click(function() {
                const resumeUrl = $(this).data('resume-url');
                const candidateName = $(this).data('candidate-name');
                viewResumeModal(resumeUrl, candidateName);
            });
        }

        // Function to open resume in modal
        function viewResumeModal(resumeUrl, candidateName) {
            // Create modal HTML
            const modalHTML = `
                <div class="modal fade" id="viewResumeModal" tabindex="-1" aria-labelledby="viewResumeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="mdi mdi-file-document me-2"></i>
                                    Resume: ${candidateName}
                                </h5>
                                <div class="d-flex gap-2">
                                    <a href="${resumeUrl}" 
                                    class="btn btn-sm btn-success"
                                    download>
                                        <i class="mdi mdi-download"></i> Download
                                    </a>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                            </div>
                            <div class="modal-body p-0">
                                <div class="resume-viewer">
                                    <!-- PDF Viewer -->
                                    <iframe src="${resumeUrl}" 
                                            style="width: 100%; height: 75vh; border: none;"
                                            title="${candidateName}'s Resume">
                                        Your browser does not support PDFs. 
                                        <a href="${resumeUrl}" download>Download the resume</a> instead.
                                    </iframe>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        <small class="text-muted">
                                            <i class="mdi mdi-information"></i>
                                            PDF Viewer - Use controls to navigate
                                        </small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#viewResumeModal').remove();
            
            // Append modal to body
            $('body').append(modalHTML);
            
            // Show modal
            $('#viewResumeModal').modal('show');
            
            // Handle modal cleanup
            $('#viewResumeModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        }

        // Clear selection - remove BOTH items
        $('#clearSelectionBtn').click(function() {
            sessionStorage.removeItem('selectedCandidates');
            sessionStorage.removeItem('candidateDetails');
            window.location.href = "{{ route('candidates.index') }}";
        });

        // Load selected candidates when page loads
        @if(request('from_candidates'))
            loadSelectedCandidates();
            
            // Debug info
            setTimeout(() => {
                console.log('Total candidates to send:', selectedCandidates.length);
                console.log('Candidate details loaded:', candidateDetails);
            }, 1000);
        @endif

        // SHARE FUNCTIONALITY
        // Generate CSV content
        function generateCSVContent() {
            const data = table.rows({ filter: 'applied' }).data();
            
            // Create CSV header
            let csvContent = "Emp Code,Name,Designation,Contact,DOJ,DOL,Status\n";
            
            // Add rows to CSV
            data.each(function (row) {
                // Get status from the 8th column (index 7)
                const status = $(row[7]).find('.badge').text() || $(row[7]).text() || '';
                
                // Clean data for CSV (remove commas, quotes, etc.)
                const cleanRow = [
                    row[1], // Emp Code
                    row[2], // Name
                    row[3], // Designation
                    row[4], // Contact
                    row[5], // DOJ
                    row[6], // DOL
                    status  // Status
                ].map(cell => {
                    // Escape quotes and wrap in quotes if contains comma
                    const cellStr = String(cell || '').trim();
                    if (cellStr.includes(',') || cellStr.includes('"') || cellStr.includes('\n')) {
                        return '"' + cellStr.replace(/"/g, '""') + '"';
                    }
                    return cellStr;
                });
                csvContent += cleanRow.join(',') + '\n';
            });
            
            return csvContent;
        }

        // Generate Excel content (base64)
        function generateExcelContent() {
            const csvContent = generateCSVContent();
            return btoa(unescape(encodeURIComponent(csvContent)));
        }

        // Generate text content for WhatsApp
        function generateTextContent() {
            const data = table.rows({ filter: 'applied' }).data();
            const currentDate = new Date().toLocaleDateString('en-GB');
            const totalRecruiters = data.count();
            
            let text = `📋 *RECRUITERS LIST*\n\n`;
            text += `📅 Date: ${currentDate}\n`;
            text += `👥 Total Recruiters: ${totalRecruiters}\n\n`;
            text += `================================\n\n`;
            
            // Add limited rows (max 20 for WhatsApp)
            let rowCount = 0;
            const maxRows = 20;
            
            data.each(function (row, index) {
                if (rowCount < maxRows) {
                    const status = $(row[7]).find('.badge').text() || $(row[7]).text() || '';
                    text += `*${index + 1}. ${row[2]}*\n`;
                    text += `Emp Code: ${row[1]}\n`;
                    text += `Designation: ${row[3]}\n`;
                    text += `Contact: ${row[4]}\n`;
                    text += `DOJ: ${row[5]} | DOL: ${row[6]}\n`;
                    text += `Status: ${status}\n`;
                    text += `--------------------------------\n`;
                    rowCount++;
                }
            });
            
            //if (totalRecruiters > maxRows) {
            //    text += `\n...and ${totalRecruiters - maxRows} more recruiters.\n`;
            //}
            
            text += `\n_Generated from Recruitment System_`;
            
            return text;
        }

        // WhatsApp Share with Data
        $('#shareWhatsAppBtn').click(function() {
            const message = generateTextContent();
            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
            
            window.open(whatsappUrl, '_blank');
            $('#shareListModal').modal('hide');
        });

        // Email Share with Data
        $('#shareEmailBtn').click(function() {
            const csvContent = generateCSVContent();
            const currentDate = new Date().toLocaleDateString('en-GB');
            const totalRecruiters = table.rows({ filter: 'applied' }).count();
            
            // Create CSV blob
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const dataUrl = URL.createObjectURL(blob);
            
            // Create download link
            const link = document.createElement('a');
            const fileName = `Recruiters_List_${currentDate.replace(/\//g, '-')}.csv`;
            link.href = dataUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Prepare email with CSV data in body
            setTimeout(() => {
                const subject = `Recruiters List - ${currentDate} (${totalRecruiters} Recruiters)`;
                let body = `Dear Team,\n\n`;
                body += `Please find attached the recruiters list CSV file.\n\n`;
                body += `Summary:\n`;
                body += `- Total Recruiters: ${totalRecruiters}\n`;
                body += `- Date: ${currentDate}\n\n`;
                body += `You can also view the CSV data below:\n\n`;
                
                // Add first 10 rows as text preview
                const data = table.rows({ filter: 'applied' }).data();
                let preview = '';
                let previewCount = 0;
                data.each(function (row) {
                    if (previewCount < 10) {
                        const status = $(row[7]).find('.badge').text() || $(row[7]).text() || '';
                        preview += `${row[1]}, ${row[2]}, ${row[3]}, ${row[4]}, ${row[5]}, ${row[6]}, ${status}\n`;
                        previewCount++;
                    }
                });
                
                if (previewCount > 0) {
                    body += `Preview (first ${previewCount} rows):\n`;
                    body += `Emp Code, Name, Designation, Contact, DOJ, DOL, Status\n`;
                    body += `--------------------------------------------------------\n`;
                    body += preview + '\n';
                }
                
                if (totalRecruiters > 10) {
                    body += `...and ${totalRecruiters - 10} more records in the attached CSV file.\n\n`;
                }
                
                body += `Best regards,\n`;
                body += `${'{{ auth()->user()->name }}'}\n`;
                body += `Recruitment Team`;
                
                const mailtoLink = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
                window.location.href = mailtoLink;
            }, 500);
            
            $('#shareListModal').modal('hide');
        });

        // Download Excel
        $('#downloadExcelBtn').click(function() {
            // Trigger Excel export
            $('.buttons-excel').click();
            $('#shareListModal').modal('hide');
        });

        // Download CSV
        $('#downloadCSVBtn').click(function() {
            // Trigger CSV export
            $('.buttons-csv').click();
            $('#shareListModal').modal('hide');
        });

        // Download PDF
        $('#downloadPDFBtn').click(function() {
            // Trigger PDF export
            $('.buttons-pdf').click();
            $('#shareListModal').modal('hide');
        });
        $('#rec_type').change(function() {
                const recType = $(this).val();
                const sendToSelect = $('#send_to');
                const loadingDiv = $('#send_to_loading');
                const sendEmailBtn = $('#sendEmailBtn');
                const additionalFields = $('#additional_fields');

                if (!recType) {
                    sendToSelect.empty().append('<option value="">Select the type first</option>').prop('disabled', true);
                    sendEmailBtn.prop('disabled', true);
                    additionalFields.addClass('d-none');
                    return;
                }

                // Show loading
                loadingDiv.removeClass('d-none');
                sendToSelect.prop('disabled', true);
                sendEmailBtn.prop('disabled', true);
                sendToSelect.empty().append('<option value="">Loading...</option>');

                // AJAX request to get recipients
                $.ajax({
                    url: "{{ route('get.recipients.by.type') }}",
                    type: "GET",
                    data: {
                        type: recType
                    },
                    success: function(response) {
                        sendToSelect.empty();
                        
                        if (response.success && response.data.length > 0) {
                            // Add default option
                            sendToSelect.append('<option value="">Select recipient</option>');
                            
                            // Add options
                            $.each(response.data, function(index, recipient) {
                                sendToSelect.append(
                                    $('<option></option>')
                                        .attr('value', recipient.id)
                                        .attr('data-email', recipient.email)
                                        .attr('data-name', recipient.name)
                                        .text(recipient.name + ' (' + recipient.email + ')')
                                );
                            });
                            
                            // Enable select and show additional fields
                            sendToSelect.prop('disabled', false);
                            additionalFields.removeClass('d-none');
                        } else {
                            sendToSelect.append('<option value="">No recipients found</option>');
                            sendEmailBtn.prop('disabled', true);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading recipients:', xhr);
                        sendToSelect.empty().append('<option value="">Error loading recipients</option>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load recipients. Please try again.',
                        });
                    },
                    complete: function() {
                        loadingDiv.addClass('d-none');
                    }
                });
            });
        // Load selected candidates when page loads
        @if(request('from_candidates'))
            loadSelectedCandidates();
            
            // Show welcome message
            setTimeout(() => {
                console.log('Ready to send ' + selectedCandidates.length + ' candidates to recruiters');
            }, 1000);
        @endif
    });
</script>
@endsection