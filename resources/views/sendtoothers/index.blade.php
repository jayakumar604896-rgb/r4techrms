@extends('layouts.app')

@section('title', 'Mail Send to Others')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">

<style>
	.btn-outline-warning:not(.btn-outline-light) {
    	color: #000 !important;
	}
	.btn-outline-info:not(.btn-outline-light) {
	    color: #000 !important;
	}
	.btn-outline-secondary:not(.btn-outline-light), .editable-container.editable-inline .editableform .control-group .editable-buttons .editable-cancel:not(.btn-outline-light) {
	    color:  #000 !important;
	}


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

    /* Status Badges */
    .badge-open {
        background-color: #28a745 !important;
        color: white !important;
    }

    .badge-in-progress {
        background-color: #ffc107 !important;
        color: #000 !important;
    }
	#modal_message
	{
		color:black !important;
	}
    .badge-closed {
        background-color: #dc3545 !important;
        color: white !important;
    }
    .btn-group > .btn.active
    {
    color:black;
    }
    .table > :not(caption)  {
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
    .brochure-item:hover {
        background-color: #f8f9fa;
    }

    .brochure-item label {
        cursor: pointer;
        flex-grow: 1;
    }

    .remove-brochure-btn {
        width: 24px;
        height: 24px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .remove-brochure-btn:hover {
        transform: scale(1.1);
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
            <h3 class="page-title fw-bold text-primary mb-1">EMAIL MARKETINGS</h3>
           
        </div>

        <button type="button" class="btn btn-sm send-candidates-btn shadow-sm send-to-recruiter-btn me-2">
            <i class="mdi mdi-send"></i> Send Mail
        </button>
    </div>
	<div class="row mb-3">
	    <div class="col-md-12">
	        <div class="d-flex flex-wrap gap-2 align-items-center p-2 bg-light rounded-3">
	            <span class="fw-semibold me-2 text-muted small">
	                <i class="mdi mdi-filter-variant me-1"></i>Filter by Type:
	            </span>
	            <a href="{{ route('sendtoothers.index', ['type' => 'all']) }}" 
	               class="btn btn-sm btn-outline-secondary {{ (!request()->has('type') || request('type') == 'all') ? 'active' : '' }}">
	                All Types
	            </a>
	            <a href="{{ route('sendtoothers.index', ['type' => 'international']) }}" 
	               class="btn btn-sm btn-outline-info {{ request('type') == 'international' ? 'active' : '' }}">
	                🌍 International ({{ $counts['International'] ?? 0 }})
	            </a>
	            <a href="{{ route('sendtoothers.index', ['type' => 'domestic']) }}" 
	               class="btn btn-sm btn-outline-warning {{ request('type') == 'domestic' ? 'active' : '' }}">
	                🏠 Domestic ({{ $counts['Domestic'] ?? 0 }})
	            </a>
	        </div>
	    </div>
	</div>
    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title mb-3 fw-semibold"> EMAIL MARKETINGS </h4>
            <div class="table-responsive">
                <table id="sendtoother-listing" class="table table-hover align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>S.No</th>
                            <th>User Type</th>
                            <th>To Mail</th>
                            <th>Subject</th>
                            <th>Sent by </th>
                            <th>Sent on</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brochures as $key => $brochure)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-semibold">{{ $brochure->user_type ?? '' }}</td>
                                <td class="fw-semibold">{{ $brochure->to_email }}</td>
                                <td class="fw-semibold">{{ $brochure->subject }}</td>
                                <td>{{ $users[$brochure->created_by] ?? '' }}</td>
                                <td>{{ $brochure->created_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Send Mail Modal -->
<div class="modal fade" id="shareListModal" tabindex="-1" aria-labelledby="shareListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareListModalLabel">Share Mail History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the mail history via:</p>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Email</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light">
                            <i class="mdi mdi-email text-danger"></i>
                        </span>
                        <input type="email" class="form-control" id="shareEmailInput" 
                               placeholder="Enter recipient email address" value="">
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-danger" id="shareEmailWithInputBtn">
                            <i class="mdi mdi-send me-2"></i> Send Email with PDF
                        </button>
                    </div>
                    <small class="text-muted">
                        <i class="mdi mdi-information-outline"></i> 
                        PDF report will be generated and sent as attachment
                    </small>
                </div>

                <div class="dropdown-divider my-3"></div>
                
                <div class="mt-3">
                    <label class="form-label fw-bold">Or download data in:</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="downloadExcelBtn">
                            <i class="mdi mdi-file-excel"></i> Excel
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="downloadCSVBtn">
                            <i class="mdi mdi-file-delimited"></i> CSV
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
<!-- Send Mail Modal -->
<div class="modal fade" id="sendCandidatesModal" tabindex="-1" aria-labelledby="sendCandidatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendCandidatesModalLabel">Send Mail with Brochures</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendCandidatesForm" action="{{ route('sendtoothers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
						<div class="col-md-4">
			                <label class="form-label">User Type <span class="required-star">*</span></label>
			                <select name="user_type" class="form-select" id="userTypeSelect" required>
			                    <option value="">Select User Type</option>
			                    <option value="Domestic">Domestic</option>
			                    <option value="International">International</option>
			                </select>
			            </div>
			            <div class="col-md-4" id="subDivisionContainer" style="display: none;">
			                <label class="form-label">Sub Division <span class="required-star" id="subDivisionStar">*</span></label>
			                <select name="sub_division" class="form-select" id="subDivisionSelect">
			                    <option value="">Select Sub Division</option>
			                    <option value="GULF">GULF</option>
			                    <option value="SEA">SEA</option>
			                    <option value="EUROPE">EUROPE</option>
			                    <option value="OTHERS">OTHERS</option>
			                </select>
			            </div>
                        <div class="col-md-6 mt-2">
                            <label class="form-label"><center>To mail</center></label>
                            <input type="text" name="to_mail" class="form-control" id="to_email" required>
                             <small class="text-muted">You can enter multiple email addresses separated by commas</small>
                        </div>
                        
                        <div class="col-md-3 mt-2">
                            <label class="form-label"><center>CC mail</center></label>
                            <input type="text" class="form-control" name="cc_mail" required>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="modal_subject" class="form-label"><center>Email Subject *</center></label>
                            <input type="text" class="form-control" id="modal_subject" name="subject" 
                                   value="Marketing Brochures" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_message" class="form-label">Email Content *</label>
                        <textarea class="form-control" id="modal_message" name="message" rows="10" required style="font-family: Arial, sans-serif; line-height: 1.6;">Dear Sir/Madam,
    
                            Please find attached our latest brochures for your reference.
                            
                            Best regards,
                            Marketing Team
                        </textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Attachments (All Active Brochures) - Click ✕ to remove</label>
                        <div class="brochures-list" id="brochuresList">
                            @foreach($brochurelists as $brochurelist)
                                @if($brochurelist->is_active == 1)
                                    <div class="brochure-item d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" name="brochure_files[]" value="{{ $brochurelist->file_path }}" 
                                                   id="brochure_{{ $brochurelist->id }}" checked class="me-2 brochure-checkbox">
                                            <label for="brochure_{{ $brochurelist->id }}" class="mb-0">{{ $brochurelist->name }}</label>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger remove-brochure-btn" 
                                                data-brochure-id="{{ $brochurelist->id }}">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAllBrochures">
                                <i class="mdi mdi-check-all"></i> Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBrochures">
                                <i class="mdi mdi-close-box"></i> Deselect All
                            </button>
                            <span id="selectedCount" class="ms-2 text-muted">
                                {{ count($brochurelists->where('is_active', 1)) }} brochures selected
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
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
                        <button type="button" class="btn btn-outline-secondary" id="downloadCSVBtn">
                            <i class="mdi mdi-file-delimited"></i> CSV
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
    let selectedRecipients = [];

    // User Type change handler - shows/hides Sub Division
    $('#userTypeSelect').change(function() {
        const userType = $(this).val();
        
        if (userType === 'International') {
            $('#subDivisionContainer').show();
            $('#subDivisionSelect').prop('required', true);
            $('#subDivisionStar').show();
        } else {
            $('#subDivisionContainer').hide();
            $('#subDivisionSelect').prop('required', false);
            $('#subDivisionStar').hide();
            $('#subDivisionSelect').val(''); // Clear selection
        }
        
        // Hide recipients container when user type changes
        $('#recipientSelectionContainer').hide();
        $('#recipientCount').text('0');
    });

    // Load Recipients button click
    $('#loadRecipientsBtn').click(function() {
        loadRecipients();
    });

    // Refresh recipients button
    $('#refreshRecipientsBtn').click(function() {
        loadRecipients();
    });

    // Function to load recipients
    function loadRecipients() {
        const userType = $('#userTypeSelect').val();
        const subDivision = $('#subDivisionSelect').val();
        const recipientsList = $('#recipientsList');
        const recipientCount = $('#recipientCount');
        
        // Validate user type selection
        if (!userType) {
            Swal.fire({
                icon: 'warning',
                title: 'Selection Required',
                text: 'Please select a user type first.',
                confirmButtonColor: '#6C63FF'
            });
            return;
        }

        // Validate sub division if International
        if (userType === 'International' && !subDivision) {
            Swal.fire({
                icon: 'warning',
                title: 'Sub Division Required',
                text: 'Please select a sub division for International recipients.',
                confirmButtonColor: '#6C63FF'
            });
            return;
        }

        // Show loading
        recipientsList.html(`
            <div class="text-center text-muted py-3">
                <i class="mdi mdi-loading mdi-spin"></i> Loading recipients...
            </div>
        `);

        // Show container
        $('#recipientSelectionContainer').show();

        // Update label
        const labels = {
            'Domestic': 'Domestic Recipients',
            'International': 'International Recipients - ' + subDivision,
            'both': 'All Recipients'
        };
        $('#recipientTypeLabel').text(labels[userType] || 'Recipients');

        // AJAX request to get recipients
        $.ajax({
            url: "{{ route('get.recipients.by.type') }}",
            type: "GET",
            data: { 
                user_type: userType,
                sub_division: subDivision 
            },
            success: function(response) {
                recipientsList.empty();
                
                if (response.success && response.data.length > 0) {
                    // Create table with recipients
                    let html = '<div class="table-responsive">';
                    html += '<table class="table table-sm table-hover mb-0">';
                    html += '<thead><tr>';
                    html += '<th width="40"><input type="checkbox" id="selectAllRecipientsCheckbox"></th>';
                    html += '<th>Name</th>';
                    html += '<th>Email</th>';
                    html += '<th>Type</th>';
                    if (response.data[0].sub_division) {
                        html += '<th>Sub Division</th>';
                    }
                    html += '</tr></thead><tbody>';
                    
                    $.each(response.data, function(index, recipient) {
                        const checked = selectedRecipients.includes(recipient.id) ? 'checked' : '';
                        html += `<tr>
                            <td>
                                <input type="checkbox" class="recipient-checkbox" 
                                       name="recipient_ids[]" value="${recipient.id}"
                                       data-email="${recipient.email}"
                                       data-name="${recipient.name}"
                                       data-type="${recipient.user_type}"
                                       ${checked}>
                            </td>
                            <td>${recipient.name}</td>
                            <td>${recipient.email}</td>
                            <td>
                                <span class="badge ${recipient.user_type === 'International' ? 'badge-international' : 'badge-domestic'}">
                                    ${recipient.user_type || 'N/A'}
                                </span>
                            </td>`;
                        
                        if (recipient.sub_division) {
                            html += `<td><span class="badge bg-secondary">${recipient.sub_division}</span></td>`;
                        }
                        
                        html += `</tr>`;
                    });
                    
                    html += '</tbody></table></div>';
                    recipientsList.html(html);
                    
                    // Update count
                    recipientCount.text(response.data.length);
                    
                    // Initialize select all for recipients
                    $('#selectAllRecipientsCheckbox').change(function() {
                        $('.recipient-checkbox').prop('checked', this.checked);
                        updateSelectedRecipientsCount();
                    });
                    
                    // Individual recipient checkbox change
                    $('.recipient-checkbox').change(function() {
                        updateSelectedRecipientsCount();
                    });
                    
                    // Update count for existing selections
                    updateSelectedRecipientsCount();
                    
                } else {
                    recipientsList.html(`
                        <div class="text-center text-muted py-3">
                            <i class="mdi mdi-account-off"></i>
                            <p class="mb-0">No recipients found for the selected criteria</p>
                        </div>
                    `);
                    recipientCount.text('0');
                }
            },
            error: function(xhr) {
                console.error('Error loading recipients:', xhr);
                recipientsList.html(`
                    <div class="text-center text-danger py-3">
                        <i class="mdi mdi-alert-circle"></i>
                        <p class="mb-0">Failed to load recipients. Please try again.</p>
                    </div>
                `);
                recipientCount.text('0');
            }
        });
    }

    // Function to update selected recipients count
    function updateSelectedRecipientsCount() {
        const count = $('.recipient-checkbox:checked').length;
        $('#selectedRecipientsCount').text(count);
        
        // Update the to_mail field with selected emails
        const emails = [];
        $('.recipient-checkbox:checked').each(function() {
            emails.push($(this).data('email'));
        });
        $('#to_email').val(emails.join(', '));
    }

    // Select All Recipients button
    $('#selectAllRecipients').click(function() {
        $('.recipient-checkbox').prop('checked', true);
        $('#selectAllRecipientsCheckbox').prop('checked', true);
        updateSelectedRecipientsCount();
    });

    // Deselect All Recipients button
    $('#deselectAllRecipients').click(function() {
        $('.recipient-checkbox').prop('checked', false);
        $('#selectAllRecipientsCheckbox').prop('checked', false);
        updateSelectedRecipientsCount();
    });

    // Brochure selection handlers
    $('#selectAllBrochures').click(function() {
        $('.brochure-checkbox').prop('checked', true);
        updateBrochureCount();
    });

    $('#deselectAllBrochures').click(function() {
        $('.brochure-checkbox').prop('checked', false);
        updateBrochureCount();
    });

    $('.brochure-checkbox').change(function() {
        updateBrochureCount();
    });

    function updateBrochureCount() {
        const count = $('.brochure-checkbox:checked').length;
        const total = $('.brochure-checkbox').length;
        $('#selectedCount').text(count + ' brochures selected');
    }

    // Remove brochure button
    $('.remove-brochure-btn').click(function() {
        const brochureItem = $(this).closest('.brochure-item');
        const checkbox = brochureItem.find('.brochure-checkbox');
        
        if (checkbox.is(':checked')) {
            checkbox.prop('checked', false);
            updateBrochureCount();
        }
        
        brochureItem.fadeOut(300, function() {
            $(this).remove();
            updateBrochureCount();
        });
    });

    // Initialize counts
    updateBrochureCount();
    updateSelectedRecipientsCount();

    // Enter key on sub division select triggers load
    $('#subDivisionSelect').keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#loadRecipientsBtn').click();
        }
    });
});
</script>
<script>
    function extractTextFromHTML(htmlString) {
        if (!htmlString) return '';
        
        if (typeof htmlString === 'string') {
            // Remove HTML tags
            let text = htmlString.replace(/<[^>]*>/g, ' ');
            text = text.replace(/&nbsp;/g, ' ');
            text = text.replace(/&amp;/g, '&');
            text = text.replace(/&lt;/g, '<');
            text = text.replace(/&gt;/g, '>');
            text = text.replace(/&quot;/g, '"');
            text = text.replace(/&#039;/g, "'");
            text = text.replace(/\s+/g, ' ').trim();
            return text;
        }
        
        return String(htmlString || '').trim();
    }

    // PDF Generation function for mail history
    function generateMailHistoryPDFBlob(callback) {
        try {
            const table = $('#sendtoother-listing').DataTable();
            const data = table.rows({ filter: 'applied' }).data();
            const totalRows = data.count();
            
            // Prepare table headers
            const headers = ['S.No', 'To Mail', 'Subject', 'Sent By', 'Sent On'];
            const tableBody = [headers];
            
            // Add data rows
            data.each(function(row, index) {
                tableBody.push([
                    (index + 1).toString(),
                    extractTextFromHTML(row[1]) || '', // To Mail
                    extractTextFromHTML(row[2]) || '', // Subject
                    extractTextFromHTML(row[3]) || '', // Sent By
                    extractTextFromHTML(row[4]) || ''  // Sent On
                ]);
            });

            // Create PDF document definition
            const docDefinition = {
                pageSize: 'A4',
                pageOrientation: 'portrait',
                pageMargins: [20, 20, 20, 20],
                header: {
                    text: 'Mail History Report',
                    alignment: 'center',
                    margin: [0, 10, 0, 10],
                    fontSize: 16,
                    bold: true,
                    color: '#004085'
                },
                footer: function(currentPage, pageCount) {
                    return {
                        text: `Page ${currentPage} of ${pageCount} | Generated on ${new Date().toLocaleDateString()}`,
                        alignment: 'center',
                        margin: [0, 10, 0, 10],
                        fontSize: 8,
                        color: '#666'
                    };
                },
                content: [
                    {
                        text: `Total Records: ${totalRows}`,
                        margin: [0, 0, 0, 20],
                        fontSize: 12
                    },
                    {
                        table: {
                            headerRows: 1,
                            widths: ['auto', 'auto', 'auto', 'auto', 'auto'],
                            body: tableBody
                        },
                        layout: {
                            fillColor: function(rowIndex, node, columnIndex) {
                                return (rowIndex === 0) ? '#004085' : null;
                            },
                            hLineWidth: function(i, node) {
                                return (i === 0 || i === node.table.body.length) ? 1 : 0.5;
                            },
                            vLineWidth: function(i, node) {
                                return 0.5;
                            },
                            hLineColor: function(i, node) {
                                return '#aaa';
                            },
                            vLineColor: function(i, node) {
                                return '#aaa';
                            },
                            paddingLeft: function(i, node) {
                                return 5;
                            },
                            paddingRight: function(i, node) {
                                return 5;
                            },
                            paddingTop: function(i, node) {
                                return 3;
                            },
                            paddingBottom: function(i, node) {
                                return 3;
                            }
                        }
                    }
                ],
                styles: {
                    tableHeader: {
                        bold: true,
                        fontSize: 10,
                        color: '#ffffff'
                    }
                }
            };

            // Generate PDF
            pdfMake.createPdf(docDefinition).getBlob(callback);
            
        } catch (error) {
            console.error('PDF Generation Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to generate PDF: ' + error.message
            });
        }
    }

    // Email share button click handler for mail history
    $('#shareEmailWithInputBtn').click(function() {
        const btn = $(this);
        const emailInput = $('#shareEmailInput');
        const email = emailInput.val().trim();
        
        // Validate email
        if (!email) {
            Swal.fire({
                icon: 'warning',
                title: 'Email Required',
                text: 'Please enter a recipient email address.'
            });
            return;
        }
        
        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Email',
                text: 'Please enter a valid email address.'
            });
            return;
        }
        
        const originalHtml = btn.html();
        btn.html('<i class="mdi mdi-loading mdi-spin"></i> Generating PDF...').prop('disabled', true);
        
        generateMailHistoryPDFBlob(function(blob) {
            const fileName = `Mail_History_${new Date().toLocaleDateString('en-GB').replace(/\//g, '-')}.pdf`;
            const totalRecords = $('#sendtoother-listing').DataTable().rows({ filter: 'applied' }).count();
            
            // Create FormData
            const formData = new FormData();
            formData.append('file', blob, fileName);
            formData.append('email', email);
            formData.append('total_records', totalRecords);
            formData.append('record_type', 'mail_history');
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Upload to server and send email
            $.ajax({
                url: '{{ route("mailhistory.send-pdf-email") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Sent!',
                            text: `Mail history PDF has been sent to ${email}`,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        
                        // Clear the input
                        emailInput.val('');
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to send email'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Upload error:', xhr);
                    let errorMessage = 'Failed to send email. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                },
                complete: function() {
                    btn.html(originalHtml).prop('disabled', false);
                    $('#shareListModal').modal('hide');
                }
            });
        });
    });

    // Keep the existing shareEmailBtn for backward compatibility
    $('#shareEmailBtn').click(function() {
        $('#shareEmailWithInputBtn').click();
    });

    // Add SweetAlert library if not already included
    if (typeof Swal === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
    }
    $(document).ready(function() {

        if ($.fn.dataTable.isDataTable('#sendtoother-listing')) {
            $('#sendtoother-listing').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#sendtoother-listing').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                "<'row'<'col-sm-12'B>>",
            buttons: [
                //{
                //    extend: 'csv',
                //    className: 'btn btn-sm',
                //    text: '<i class="mdi mdi-file-delimited"></i> CSV',
                //    exportOptions: {
                //        columns: [0, 1, 2, 3, 4]
                //    },
                //    title: 'Mail_History_List_' + new Date().toLocaleDateString('en-GB')
                //},
                {
                    extend: 'excel',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-excel"></i> Excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    title: 'Mail_History_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    },
                    title: 'Mail_History_List_' + new Date().toLocaleDateString('en-GB'),
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
                        columns: [0, 1, 2, 3, 4]
                    },
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

        // Send Mail Button
        $('.send-to-recruiter-btn').click(function() {
            $('#sendCandidatesModal').modal('show');
            updateSelectedCount();
        });

        // Remove brochure attachment
        $(document).on('click', '.remove-brochure-btn', function() {
            const brochureId = $(this).data('brochure-id');
            $(this).closest('.brochure-item').remove();
            updateSelectedCount();
        });

        // Select All brochures
        $('#selectAllBrochures').click(function() {
            $('.brochure-checkbox').prop('checked', true);
            updateSelectedCount();
        });

        // Deselect All brochures
        $('#deselectAllBrochures').click(function() {
            $('.brochure-checkbox').prop('checked', false);
            updateSelectedCount();
        });

        // Update selected count when checkboxes change
        $(document).on('change', '.brochure-checkbox', function() {
            updateSelectedCount();
        });

        // Function to update selected brochure count
        function updateSelectedCount() {
            const selectedCount = $('.brochure-checkbox:checked').length;
            const totalCount = $('.brochure-checkbox').length;
            $('#selectedCount').text(selectedCount + ' of ' + totalCount + ' brochures selected');
        }

        // AJAX form submission for sending mail
        //$('#sendCandidatesForm').submit(function(e){
//             e.preventDefault();

//             // Check if at least one brochure is selected
//             if ($('.brochure-checkbox:checked').length === 0) {
//                 alert('Please select at least one brochure to send.');
//                 return;
//             }

//             // Remove unchecked brochures from form data
//             $('.brochure-checkbox:not(:checked)').each(function() {
//                 $(this).closest('.brochure-item').remove();
//             });

//             let formData = $(this).serialize();

//             $.ajax({
//                 url: "{{ route('sendtoothers.store') }}",
//                 type: 'POST',
//                 data: formData,
//                 beforeSend: function() {
//                     // Show loading indicator
//                     $('button[type="submit"]').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Sending...');
//                 },
//                 success: function(res) {
//                     if(res.status == 'success') {
//                         alert(res.message);
//                         $('#sendCandidatesModal').modal('hide');
//                         location.reload(); 
//                     } else {
//                         alert(res.message);
//                     }
//                     $('button[type="submit"]').prop('disabled', false).html('<i class="mdi mdi-send"></i> Send Email');
//                 },
//                 error: function(xhr) {
//                     let errors = xhr.responseJSON.errors;
//                     let errorMsg = '';
//                     $.each(errors, function(key, val){
//                         errorMsg += val[0] + '\n';
//                     });
//                     alert(errorMsg || 'An error occurred. Please try again.');
//                     $('button[type="submit"]').prop('disabled', false).html('<i class="mdi mdi-send"></i> Send Email');
//                 }
//             });
//         });
$('.send-to-recruiter-btn').click(function() {
        $('#sendCandidatesModal').modal('show');
    });

    // AJAX form submission
    $('#sendCandidatesForm').submit(function(e){
        e.preventDefault();
        
        // Get selected brochures
        let selectedBrochures = [];
        $('.brochure-checkbox:checked').each(function() {
            selectedBrochures.push($(this).val());
        });
        
        // Validation
        if (selectedBrochures.length === 0) {
            alert('Please select at least one brochure.');
            return false;
        }
        
        // Show loading
        const submitBtn = $('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Sending...');
        
        // Get form data
        let formData = new FormData(this);
        
        // Clear existing brochure_files and add only selected ones
        formData.delete('brochure_files[]');
        selectedBrochures.forEach(function(path) {
            formData.append('brochure_files[]', path);
        });
        
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
                    
                    // Reload page after 1 second
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert(response.message || 'Error sending mail.');
                }
                submitBtn.prop('disabled', false).html(originalText);
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Simple brochure selection handling
    $(document).on('click', '.remove-brochure-btn', function() {
        const brochureId = $(this).data('brochure-id');
        $(`#brochure_${brochureId}`).prop('checked', false);
        updateSelectedCount();
    });
    
    $('#selectAllBrochures').click(function() {
        $('.brochure-checkbox').prop('checked', true);
        updateSelectedCount();
    });
    
    $('#deselectAllBrochures').click(function() {
        $('.brochure-checkbox').prop('checked', false);
        updateSelectedCount();
    });
    
    $(document).on('change', '.brochure-checkbox', updateSelectedCount);
    
    function updateSelectedCount() {
        const selected = $('.brochure-checkbox:checked').length;
        const total = $('.brochure-checkbox').length;
        $('#selectedCount').text(`${selected} of ${total} brochures selected`);
    }
    
    // Initialize count
    updateSelectedCount();

        // SHARE FUNCTIONALITY
        // Generate CSV content
        function generateCSVContent() {
            const data = table.rows({ filter: 'applied' }).data();
            
            // Create CSV header
            let csvContent = "S.No,To Email,Subject,Sent By,Sent At\n";
            
            // Add rows to CSV
            data.each(function (row, index) {
                // Clean data for CSV
                const cleanRow = [
                    index + 1,
                    row[1], // To Email
                    row[2], // Subject
                    row[3], // Sent By
                    row[4]  // Sent At
                ].map(cell => {
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

        // Generate text content for WhatsApp
        function generateTextContent() {
            const data = table.rows({ filter: 'applied' }).data();
            const currentDate = new Date().toLocaleDateString('en-GB');
            const totalRecords = data.count();
            
            let text = `📧 *MAIL HISTORY REPORT*\n\n`;
            text += `📅 Date: ${currentDate}\n`;
            text += `📊 Total Records: ${totalRecords}\n\n`;
            text += `================================\n\n`;
            
            // Add limited rows (max 15 for WhatsApp)
            let rowCount = 0;
            const maxRows = 15;
            
            data.each(function (row, index) {
                if (rowCount < maxRows) {
                    text += `*${index + 1}. ${row[1]}*\n`;
                    text += `Subject: ${row[2]}\n`;
                    text += `Sent By: ${row[3]}\n`;
                    text += `Sent At: ${row[4]}\n`;
                    text += `--------------------------------\n`;
                    rowCount++;
                }
            });
            
            if (totalRecords > maxRows) {
                text += `\n...and ${totalRecords - maxRows} more records.\n`;
            }
            
            text += `\n_Generated from Marketing System_`;
            
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
            const totalRecords = table.rows({ filter: 'applied' }).count();
            
            // Create CSV blob
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const dataUrl = URL.createObjectURL(blob);
            
            // Create download link
            const link = document.createElement('a');
            const fileName = `Mail_History_${currentDate.replace(/\//g, '-')}.csv`;
            link.href = dataUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Prepare email
            setTimeout(() => {
                const subject = `Mail History Report - ${currentDate}`;
                let body = `Dear Team,\n\n`;
                body += `Please find attached the mail history report.\n\n`;
                body += `Report Summary:\n`;
                body += `- Total Records: ${totalRecords}\n`;
                body += `- Report Date: ${currentDate}\n`;
                body += `- Generated By: {{ auth()->user()->name }}\n\n`;
                
                // Add first 5 rows as text preview
                const data = table.rows({ filter: 'applied' }).data();
                let preview = '';
                let previewCount = 0;
                data.each(function (row) {
                    if (previewCount < 5) {
                        preview += `• To: ${row[1]}, Subject: ${row[2]}, Sent: ${row[4]}\n`;
                        previewCount++;
                    }
                });
                
                if (previewCount > 0) {
                    body += `Recent Mail History (${previewCount} of ${totalRecords}):\n`;
                    body += preview + '\n';
                }
                
                body += `Full details are available in the attached CSV file.\n\n`;
                body += `Best regards,\n`;
                body += `Marketing Team`;
                
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
    });
</script>
@endsection