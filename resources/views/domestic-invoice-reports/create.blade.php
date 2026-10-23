@extends('layouts.app')

@section('title', 'Create Invoice')
@section('content')
<style>
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        background: linear-gradient(45deg, #004085, #002752);
        color: white;
        border-bottom: none;
        padding: 1.5rem;
    }
    .card-body {
        padding: 2rem;
    }
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.75rem;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #004085;
        box-shadow: 0 0 0 0.25rem rgba(0, 64, 133, 0.25);
    }
    .required::after {
        content: " *";
        color: #dc3545;
    }
    .calculation-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
    .calculation-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #dee2e6;
    }
    .calculation-item:last-child {
        border-bottom: none;
        font-weight: bold;
        font-size: 1.1rem;
        color: #004085;
    }
    .calculation-label {
        color: #6c757d;
    }
    .calculation-value {
        font-weight: 600;
        color: #495057;
    }
    .preview-box {
        background: white;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        min-height: 200px;
    }
    .invoice-preview {
        font-family: 'Courier New', monospace;
    }
    .invoice-header {
        border-bottom: 2px solid #004085;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    .invoice-detail {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .invoice-total {
        background: #004085;
        color: white;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1rem;
        font-weight: bold;
    }
    .btn-submit {
        background: linear-gradient(45deg, #004085, #002752);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 64, 133, 0.3);
    }
    .btn-cancel {
        background: #6c757d;
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }
    .btn-cancel:hover {
        background: #5a6268;
    }
    .field-group {
        margin-bottom: 1.5rem;
    }
    .gst-fields {
        background: #f0f8ff;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #cfe2ff;
    }
    .client-info-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 1rem;
        margin-top: 0.5rem;
        display: none;
    }
    .client-info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.25rem 0;
        font-size: 0.9rem;
    }
    .client-info-label {
        color: #6c757d;
        font-weight: 500;
    }
    .client-info-value {
        color: #495057;
        font-weight: 600;
    }
    .add-client-link {
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
    .client-select-with-add {
        position: relative;
    }
    .quick-add-btn {
        position: absolute;
        right: 35px;
        top: 0;
        height: 100%;
        background: transparent;
        border: none;
        color: #004085;
        padding: 0 10px;
    }
    .form-text{
        font-size: 0.75em !important;
    }
    .card .card-title
    {
        color:#fff;
    }
    .btn{
        color:#fff;
    }
</style>

<div class="content-wrapper">

    {{-- Success/Error Messages --}}
    <div class="row mb-3">
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="mdi mdi-alert-circle"></i> Please fix the following errors:
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Page Header --}}
    <div class="page-header mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1"> Create New Invoice </h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('domestic-invoice-reports.index') }}">Domestic Invoice Reports</a></li>
                <li class="breadcrumb-item active">Create Invoice</li>
            </ol>
        </div>
    </div>

    {{-- Main Form --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Invoice Information</h4>
                    <p class="mb-0 opacity-75">Fill in all required fields marked with *</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('domestic-invoice-reports.store') }}" method="POST" id="invoiceForm" novalidate>
                        @csrf
                        
                        {{-- Invoice Details --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label for="invoice_no" class="form-label required">Invoice Number</label>
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no" 
                                           value="{{ old('invoice_no') }}" required 
                                           placeholder="e.g., INV-2024-001">
                                    <small class="form-text text-muted">Unique invoice reference number</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label for="invoice_date" class="form-label required">Invoice Date</label>
                                    <input type="date" class="form-control" id="invoice_date" name="invoice_date" 
                                           value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Client Information --}}
                        <div class="field-group">
                            <label for="client_id" class="form-label required">Select Client</label>
                            <div class="client-select-with-add">
                                <select class="form-select" id="client_id" name="client_id" required>
                                    <option value="">-- Select a Client --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" 
                                                data-desination="{{ $client->desination ?? '' }}"
                                                data-email="{{ $client->email_id ?? '' }}"
                                                data-phone="{{ $client->mobile_number ?? '' }}"
                                                data-address="{{ $client->company_location ?? '' }}"
                                                data-gst="{{ $client->gst_number ?? '' }}"
                                                {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->contact_person }}
                                            @if($client->company_name)
                                                ({{ $client->company_name }})
                                            @endif
                                        </option>
                                    @endforeach
                                    <option value="new">+ Add New Client</option>
                                </select>
                                <button type="button" class="quick-add-btn" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                    <i class="mdi mdi-plus-circle"></i>
                                </button>
                            </div>
                            
                            {{-- Quick Add New Client Link --}}
                            <a href="{{ route('clients.create') }}" target="_blank" class="add-client-link">
                                <i class="mdi mdi-plus"></i> Add new client
                            </a>
                            
                            {{-- Client Information Display --}}
                            <div id="clientInfoBox" class="client-info-box">
                                <h6 class="border-bottom pb-2 mb-2">Client Information</h6>
                                <div class="client-info-item">
                                    <span class="client-info-label">Designation:</span>
                                    <span class="client-info-value" id="clientDesination">-</span>
                                </div>
                                <div class="client-info-item">
                                    <span class="client-info-label">Email:</span>
                                    <span class="client-info-value" id="clientEmail">-</span>
                                </div>
                                <div class="client-info-item">
                                    <span class="client-info-label">Phone:</span>
                                    <span class="client-info-value" id="clientPhone">-</span>
                                </div>
                                <div class="client-info-item">
                                    <span class="client-info-label">Address:</span>
                                    <span class="client-info-value" id="clientAddress">-</span>
                                </div>
                                <div class="client-info-item">
                                    <span class="client-info-label">GST:</span>
                                    <span class="client-info-value" id="clientGST">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- PAN & LIC Numbers --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="field-group">
                                    <label for="pan_no" class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" id="pan_no" name="pan_no" 
                                           value="{{ old('pan_no') }}" 
                                           placeholder="Enter PAN number"
                                           maxlength="10">
                                    <small class="form-text text-muted">10 character PAN number</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-group">
                                    <label for="lic_no" class="form-label">GST Number</label>
                                    <input type="text" class="form-control" id="lic_no" name="lic_no" 
                                           value="{{ old('lic_no') }}" 
                                           placeholder="GST reference number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-group">
                                    <label for="tan" class="form-label">TAN Number</label>
                                    <input type="text" class="form-control" id="tan" name="tan" 
                                           value="{{ old('tan') }}" 
                                           placeholder="tan reference number">
                                </div>
                            </div>
                        </div>

                        {{-- Particulars --}}
                        <div class="field-group">
                            <label for="particular" class="form-label required">Particulars</label>
                            <textarea class="textarea form-control" id="particular" name="particular" rows="3" 
                                      required placeholder="Description of goods/services">{{ old('particular') }}</textarea>
                            <small class="form-text text-muted">Detailed description of the invoice items</small>
                        </div>

                        {{-- Quantity and Rate --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="field-group">
                                    <label for="nos" class="form-label required">Quantity (Nos)</label>
                                    <input type="number" class="form-control" id="nos" name="nos" 
                                           value="{{ old('nos', 1) }}" required min="1" step="1"
                                           placeholder="Quantity">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-group">
                                    <label for="unit_rate" class="form-label required">Unit Rate (₹)</label>
                                    <input type="number" class="form-control" id="unit_rate" name="unit_rate" 
                                           value="{{ old('unit_rate', 0) }}" required min="0" step="0.01"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-group">
                                    <label for="total_amount" class="form-label">Total Amount (₹)</label>
                                    <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                           value="{{ old('total_amount', 0) }}" readonly
                                           placeholder="Auto-calculated">
                                    <small class="form-text text-muted">Quantity × Unit Rate</small>
                                </div>
                            </div>
                        </div>

                        {{-- GST Section --}}
                        <div class="gst-fields mt-4">
                            <h5 class="mb-3">GST Details</h5>
                            
                            {{-- IGST Toggle Checkbox --}}
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="apply_igst" name="apply_igst">
                                <label class="form-check-label" for="apply_igst">
                                    Show IGST (Inter‑state)
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="field-group">
                                        <label for="cgst" class="form-label">CGST (9%)</label>
                                        <input type="number" class="form-control" id="cgst" name="cgst" readonly placeholder="0.00">
                                        <small class="form-text text-muted">Central GST percentage</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field-group">
                                        <label for="sgst" class="form-label">SGST (9%)</label>
                                        <input type="number" class="form-control" id="sgst" name="sgst" readonly placeholder="0.00">
                                        <small class="form-text text-muted">State GST percentage</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field-group">
                                        <label for="igst" class="form-label">IGST (18%)</label>
                                        <input type="number" class="form-control" id="igst" name="igst" readonly placeholder="0.00">
                                        <small class="form-text text-muted">Integrated GST for inter‑state sales</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Net Amount --}}
                        <div class="field-group mt-4">
                            <label for="net_amount" class="form-label">Net Amount (₹)</label>
                            <input type="number" class="form-control" id="net_amount" name="net_amount" 
                                   value="{{ old('net_amount', 0) }}" readonly
                                   placeholder="Auto-calculated">
                            <small class="form-text text-muted">Total Amount + GST</small>
                        </div>

                        {{-- Created By --}}
                        <div class="field-group">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="hidden" id="created_by" name="created_by" value="{{ auth()->id() ?? '' }}">
                            <input type="text" class="form-control" value="{{ auth()->user()->name ?? 'System' }}" disabled>
                            <small class="form-text text-muted">Current logged in user</small>
                        </div>

                        {{-- Form Actions --}}
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('domestic-invoice-reports.index') }}" class="btn btn-cancel">
                                <i class="mdi mdi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-submit">
                                <i class="mdi mdi-content-save"></i> Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Preview Panel --}}
        <div class="col-lg-4">
            {{-- Calculation Summary --}}
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Calculation Summary</h5>
                </div>
                <div class="card-body">
                    <div class="calculation-box">
                        <div class="calculation-item">
                            <span class="calculation-label">Quantity × Unit Rate:</span>
                            <span class="calculation-value" id="calc_base_amount">₹0.00</span>
                        </div>
                        <div class="calculation-item cgst-item">
                            <span class="calculation-label">CGST (9%):</span>
                            <span class="calculation-value" id="calc_cgst_amount">₹0.00</span>
                        </div>
                        <div class="calculation-item sgst-item">
                            <span class="calculation-label">SGST (9%):</span>
                            <span class="calculation-value" id="calc_sgst_amount">₹0.00</span>
                        </div>
                        <div class="calculation-item igst-item" style="display: none;">
                            <span class="calculation-label">IGST (18%):</span>
                            <span class="calculation-value" id="calc_igst_amount">₹0.00</span>
                        </div>
                        <div class="calculation-item">
                            <span class="calculation-label">Total GST:</span>
                            <span class="calculation-value" id="calc_total_gst">₹0.00</span>
                        </div>
                        <div class="calculation-item">
                            <span class="calculation-label">Net Amount:</span>
                            <span class="calculation-value text-primary fw-bold" id="calc_net_amount">₹0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Preview --}}
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Invoice Preview</h5>
                </div>
                <div class="card-body">
                    <div class="preview-box">
                        <div class="invoice-preview">
                            <div class="invoice-header text-center">
                                <h6 class="mb-1">INVOICE PREVIEW</h6>
                                <small class="text-muted">This is how your invoice will look</small>
                            </div>
                            <div class="invoice-detail">
                                <span>Invoice #:</span>
                                <span id="preview_invoice_no" class="fw-bold">INV-XXXX-XXX</span>
                            </div>
                            <div class="invoice-detail">
                                <span>Date:</span>
                                <span id="preview_date">{{ date('d M, Y') }}</span>
                            </div>
                            <div class="invoice-detail">
                                <span>Client:</span>
                                <span id="preview_client">-</span>
                            </div>
                            <div class="invoice-detail">
                                <span>Particulars:</span>
                                <span id="preview_particular" class="text-truncate d-inline-block" style="max-width: 150px;">-</span>
                            </div>
                            <div class="invoice-detail">
                                <span>Quantity:</span>
                                <span id="preview_quantity">0</span>
                            </div>
                            <div class="invoice-detail">
                                <span>Unit Rate:</span>
                                <span id="preview_rate">₹0.00</span>
                            </div>
                            <div class="invoice-detail">
                                <span>Total:</span>
                                <span id="preview_total">₹0.00</span>
                            </div>
                            <div class="invoice-detail">
                                <span>GST:</span>
                                <span id="preview_gst">₹0.00</span>
                            </div>
                            <div class="invoice-total text-center">
                                <span>Net Amount: </span>
                                <span id="preview_net_amount" class="fs-5">₹0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Tips --}}
            <div class="alert alert-info mt-4" role="alert">
                <h6 class="alert-heading"><i class="mdi mdi-information"></i> Tips</h6>
                <ul class="mb-0">
                    <li>All fields marked with * are required</li>
                    <li>Select a client from the dropdown or add new</li>
                    <li>GST percentages are optional</li>
                    <li>Amounts are calculated automatically</li>
                    <li>Invoice number must be unique</li>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- Add Client Modal (Optional) --}}
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Quick Add Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quickClientForm">
                    <div class="mb-3">
                        <label for="quick_client_name" class="form-label">Client Name *</label>
                        <input type="text" class="form-control" id="quick_client_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="quick_client_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="quick_client_email">
                    </div>
                    <div class="mb-3">
                        <label for="quick_client_phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="quick_client_phone">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveQuickClient">Save Client</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Format currency
        function formatCurrency(amount) {
            return '₹' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        // Calculate all amounts based on IGST checkbox
        function calculateAmounts() {
            const nos = parseFloat($('#nos').val()) || 0;
            const unitRate = parseFloat($('#unit_rate').val()) || 0;
            const applyIgst = $('#apply_igst').is(':checked');

            const baseAmount = nos * unitRate;
            let cgstAmount = 0, sgstAmount = 0, igstAmount = 0;

            if (applyIgst) {
                // IGST mode: 18% on base
                igstAmount = baseAmount * 0.18;
            } else {
                // CGST+SGST mode: 9% each
                cgstAmount = baseAmount * 0.09;
                sgstAmount = baseAmount * 0.09;
            }

            const totalGST = cgstAmount + sgstAmount + igstAmount;
            const netAmount = baseAmount + totalGST;

            // Update input fields
            $('#cgst').val(cgstAmount.toFixed(2));
            $('#sgst').val(sgstAmount.toFixed(2));
            $('#igst').val(igstAmount.toFixed(2));
            $('#total_amount').val(baseAmount.toFixed(2));
            $('#net_amount').val(netAmount.toFixed(2));

            // Update calculation summary values
            $('#calc_base_amount').text(formatCurrency(baseAmount));
            $('#calc_cgst_amount').text(formatCurrency(cgstAmount));
            $('#calc_sgst_amount').text(formatCurrency(sgstAmount));
            $('#calc_igst_amount').text(formatCurrency(igstAmount));
            $('#calc_total_gst').text(formatCurrency(totalGST));
            $('#calc_net_amount').text(formatCurrency(netAmount));

            // Toggle visibility of summary items
            if (applyIgst) {
                $('.cgst-item, .sgst-item').hide();
                $('.igst-item').show();
            } else {
                $('.cgst-item, .sgst-item').show();
                $('.igst-item').hide();
            }

            // Update preview
            updatePreview();
        }

        // Update the invoice preview
        function updatePreview() {
            const invoiceNo = $('#invoice_no').val() || 'INV-XXXX-XXX';
            const invoiceDate = $('#invoice_date').val();
            const clientSelect = $('#client_id option:selected');
            const clientName = clientSelect.text() || '-';
            const particular = $('#particular').val() || '-';
            const nos = $('#nos').val() || 0;
            const unitRate = parseFloat($('#unit_rate').val()) || 0;
            const totalAmount = parseFloat($('#total_amount').val()) || 0;
            const totalGST = parseFloat($('#calc_total_gst').text().replace('₹', '')) || 0;
            const netAmount = parseFloat($('#net_amount').val()) || 0;

            // Format date
            let formattedDate = invoiceDate;
            if (invoiceDate) {
                const dateObj = new Date(invoiceDate);
                formattedDate = dateObj.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }

            // Truncate long text
            const shortParticular = particular.length > 30 ? particular.substring(0, 30) + '...' : particular;
            const shortClient = clientName.length > 20 ? clientName.substring(0, 20) + '...' : clientName;

            $('#preview_invoice_no').text(invoiceNo);
            $('#preview_date').text(formattedDate);
            $('#preview_client').text(shortClient);
            $('#preview_particular').text(shortParticular);
            $('#preview_quantity').text(nos);
            $('#preview_rate').text(formatCurrency(unitRate));
            $('#preview_total').text(formatCurrency(totalAmount));
            $('#preview_gst').text(formatCurrency(totalGST));
            $('#preview_net_amount').text(formatCurrency(netAmount));
        }

        // Event listeners
        $('#nos, #unit_rate, #apply_igst').on('input change', function() {
            calculateAmounts();
        });

        // Handle client selection change
        $('#client_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const clientId = $(this).val();
            
            if (clientId === 'new') {
                window.open("{{ route('clients.create') }}", '_blank');
                $(this).val('');
                $('#clientInfoBox').hide();
                return;
            }
            
            if (clientId && clientId !== '') {
                $('#clientInfoBox').show();
                
                $('#clientDesination').text(selectedOption.data('desination') || '-');
                $('#clientEmail').text(selectedOption.data('email') || '-');
                $('#clientPhone').text(selectedOption.data('phone') || '-');
                $('#clientAddress').text(selectedOption.data('address') || '-');
                $('#clientGST').text(selectedOption.data('gst') || '-');
                
                updatePreview();
            } else {
                $('#clientInfoBox').hide();
            }
        });

        // Auto-generate invoice number if empty
        if (!$('#invoice_no').val()) {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            $('#invoice_no').val(`INV-${year}-${month}-${random}`);
        }

        // Set today's date if empty
        if (!$('#invoice_date').val()) {
            const today = new Date().toISOString().split('T')[0];
            $('#invoice_date').val(today);
        }

        // PAN validation
        $('#pan_no').on('input', function() {
            const pan = $(this).val().toUpperCase().replace(/\s/g, '');
            $(this).val(pan);
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            if (pan.length > 0) {
                const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                if (!panRegex.test(pan)) {
                    $(this).addClass('is-invalid');
                    $(this).after('<div class="invalid-feedback">Please enter a valid 10-character PAN (e.g., ABCDE1234F)</div>');
                }
            }
        });

        // Quick add client (dummy)
        $('#saveQuickClient').click(function() {
            const name = $('#quick_client_name').val();
            const email = $('#quick_client_email').val();
            const phone = $('#quick_client_phone').val();
            if (!name) {
                alert('Client name is required');
                return;
            }
            const newOption = new Option(name, 'temp_' + Date.now(), true, true);
            $('#client_id').append(newOption).trigger('change');
            $('#addClientModal').modal('hide');
            $('#quickClientForm')[0].reset();
        });

        // Form submission validation
        $('#invoiceForm').attr('novalidate', 'novalidate');
        $('#invoiceForm').on('submit', function(e) {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
            let isValid = true;
            const requiredFields = [
                { selector: '#invoice_no', message: 'Invoice number is required.' },
                { selector: '#client_id', message: 'Please select a client.' },
                { selector: '#particular', message: 'Please enter particulars.' },
                { selector: '#nos', message: 'Quantity is required.' },
                { selector: '#unit_rate', message: 'Unit rate is required.' }
            ];
            requiredFields.forEach(field => {
                const $el = $(field.selector);
                let value = '';
                if (field.selector === '#client_id') {
                    value = $el.val();
                } else if (field.selector === '#particular') {
                    if (typeof tinymce !== 'undefined' && tinymce.get('particular')) {
                        value = tinymce.get('particular').getContent();
                    } else {
                        value = $el.val();
                    }
                } else {
                    value = $el.val();
                }
                if (!value || (typeof value === 'string' && !value.trim())) {
                    isValid = false;
                    $el.addClass('is-invalid');
                    if (!$el.next('.invalid-feedback').length) {
                        $el.after(`<div class="invalid-feedback">${field.message}</div>`);
                    }
                } else {
                    $el.removeClass('is-invalid');
                    $el.next('.invalid-feedback').remove();
                }
            });
            if (!isValid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
            }
        });

        // Initial calculations
        calculateAmounts();
        $('#invoice_no').focus();
        if ($('#client_id').val()) {
            $('#client_id').trigger('change');
        }
    });
</script>
@include('shared.tinyMCEFront')
@endsection