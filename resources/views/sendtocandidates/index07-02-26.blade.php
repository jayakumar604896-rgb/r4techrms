@extends('layouts.app')

@section('title', 'Mail Send to Others')
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

    /* Status Badges */
    .badge-open {
        background-color: #28a745 !important;
        color: white !important;
    }

    .badge-in-progress {
        background-color: #ffc107 !important;
        color: #000 !important;
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
                            <th>Send By</th>
                            <th>Send At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brochures as $key => $brochure)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-semibold">{{ $brochure->to_email }}</td>
                                <td class="fw-semibold">{{ $brochure->subject }}</td>
                                <td>{{ $users[$brochure->created_by] ?? '' }}</td>
                                <td>{{ $brochure->created_at->format('d M, Y') }}</td>
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
                <h5 class="modal-title" id="sendCandidatesModalLabel">Send Mail with Candidate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendCandidatesForm" action="{{ route('sendtocandidates.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><center>To mail</center></label>
                            <select name="to_mail" class="form-control" id="to_email" required>
                                <option value="">Please Select Candidate</option>
                                @foreach($candidates as $candidate)
                                <option value={{$candidate->email}}>{{$candidate->candidate_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
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

                    <!--<div class="mb-3">
                        <label class="form-label">Attachments (All Active Brochures) - Click ✕ to remove</label>
                        <div class="brochures-list" id="brochuresList">
                            @foreach($brochurelists as $brochurelist)
                                @if($brochurelist->is_active == 1)
                                    <div class="brochure-item d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" name="brochure_files[]" value="{{ $brochurelist->file_path }}" 
                                                   id="brochure_{{ $brochurelist->id }}" checked class="me-2 brochure-checkbox">
                                            <label for="brochure_{{ $brochurelist->id }}" class="mb-0">{{ $brochurelist->file_name }}</label>
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
                    </div>-->
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
<script>
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
        $('#sendCandidatesForm').submit(function(e){
            e.preventDefault();

            // Check if at least one brochure is selected
            

            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('sendtocandidates.store') }}",
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    // Show loading indicator
                    $('button[type="submit"]').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Sending...');
                },
                success: function(res) {
                    if(res.status == 'success') {
                        alert(res.message);
                        $('#sendCandidatesModal').modal('hide');
                        location.reload(); 
                    } else {
                        alert(res.message);
                    }
                    $('button[type="submit"]').prop('disabled', false).html('<i class="mdi mdi-send"></i> Send Email');
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, val){
                        errorMsg += val[0] + '\n';
                    });
                    alert(errorMsg || 'An error occurred. Please try again.');
                    $('button[type="submit"]').prop('disabled', false).html('<i class="mdi mdi-send"></i> Send Email');
                }
            });
        });

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