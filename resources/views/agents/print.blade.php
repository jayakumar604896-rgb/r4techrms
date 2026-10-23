<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<style>
    :root {
        --primary-color: #4361ee;
        --primary-light: #eef2ff;
        --secondary-color: #3a0ca3;
        --accent-color: #7209b7;
        --text-dark: #2b2d42;
        --text-light: #8d99ae;
        --border-color: #e9ecef;
        --success-color: #06d6a0;
        --warning-color: #ffd166;
        --card-bg: #ffffff;
    }

    .page-header h3 {
        font-weight: 700;
        font-size: 28px;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0;
    }

    .client-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .client-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(80px, -80px);
    }

    .client-avatar {
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        margin-right: 25px;
        border: 4px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }

    .client-info h2 {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .client-info .badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 8px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .detail-card {
        border-radius: 18px;
        border: none;
        background: var(--card-bg);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-light), white);
        border-bottom: 3px solid var(--primary-color);
        padding: 20px 25px;
    }

    .card-header-custom h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-custom h5 i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .card-body-custom {
        padding: 25px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
        gap: 20px;
    }
    .info-company-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        padding: 15px;
        border-radius: 12px;
        background: var(--primary-light);
        transition: all 0.3s;
        border-left: 4px solid var(--primary-color);
    }
    .info-item-details {
        display: flex;
        flex-direction: column;
        padding: 15px;
        border-radius: 12px;
        background: var(--primary-light);
        transition: all 0.3s;
        border-left: 4px solid var(--primary-color);
        margin-bottom: 15px;
    }

    .info-item:hover {
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }
    .info-item-details:hover {
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }

    .info-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        word-break: break-word;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-success { background: #e8f5e9; color: #2e7d32; }
    .badge-danger { background: #ffebee; color: #c62828; }
    .badge-info { background: #e0f7fa; color: #006064; }
    .badge-secondary { background: #f5f5f5; color: #616161; }

    .resume-card {
        background: linear-gradient(135deg, var(--primary-light), white);
        border: 2px dashed var(--primary-color);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s;
    }

    .resume-card:hover {
        border-color: var(--accent-color);
        transform: scale(1.02);
    }

    .resume-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 30px;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        color: white;
    }

    .btn-back {
        background: white;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .btn-print {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(240, 147, 251, 0.3);
        color: white;
    }

    .content-wrapper{
        padding:50px;
        margin-top:10px;
    }
    .details{
        margin-top:45px;
    }

    /* Print Styles - Optimized for Single Page */
    @media print {
        body {
            background: white !important;
            color: black !important;
            font-family: 'Arial', sans-serif;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        * {
            box-sizing: border-box !important;
        }
        
        .content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        
        /* Hide elements not needed in print */
        .action-buttons,
        .breadcrumb,
        .page-header,
        .btn-edit,
        .btn-back,
        .btn-print,
        .detail-card:hover,
        .client-header::before {
            display: none !important;
        }
        
        /* Print header */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 10px !important;
            padding-bottom: 10px !important;
            border-bottom: 2px solid #000 !important;
            page-break-after: avoid !important;
        }
        
        .print-header h1 {
            font-size: 24px !important;
            color: #000 !important;
            margin: 0 0 5px 0 !important;
            padding: 0 !important;
        }
        
        .print-date {
            font-size: 12px !important;
            color: #666 !important;
            margin: 0 !important;
        }
        
        /* Adjust client header for print */
        .client-header {
            background: none !important;
            color: black !important;
            border: 1px solid #000 !important;
            border-radius: 5px !important;
            padding: 10px !important;
            margin: 10px 0 15px 0 !important;
            min-height: auto !important;
            page-break-inside: avoid !important;
        }
        
        .client-avatar {
            width: 60px !important;
            height: 60px !important;
            font-size: 24px !important;
            margin-right: 15px !important;
            background: #f0f0f0 !important;
            color: #000 !important;
            border: 1px solid #000 !important;
            float: left !important;
        }
        
        .client-info .badge {
            display: inline-block !important;
            background: #f0f0f0 !important;
            color: #000 !important;
            border: 1px solid #000 !important;
            padding: 3px 8px !important;
            margin: 2px !important;
            font-size: 11px !important;
        }
        
        .details {
            margin-top: 0 !important;
            overflow: hidden !important;
        }
        
        /* Compact the main content */
        .row {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            page-break-inside: avoid !important;
        }
        
        .col-lg-6,
        .col-md-6 {
            float: none !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            page-break-inside: avoid !important;
        }
        
        /* Cards for print */
        .detail-card {
            box-shadow: none !important;
            border: 1px solid #000 !important;
            border-radius: 5px !important;
            margin: 0 0 10px 0 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        
        .detail-card:hover {
            transform: none !important;
        }
        
        .card-header-custom {
            background: #f0f0f0 !important;
            border-bottom: 1px solid #000 !important;
            padding: 8px 10px !important;
            margin: 0 !important;
        }
        
        .card-header-custom h5 {
            color: #000 !important;
            font-size: 16px !important;
            margin: 0 !important;
        }
        
        .card-header-custom h5 i {
            color: #000 !important;
            font-size: 16px !important;
        }
        
        .card-body-custom {
            padding: 10px !important;
            margin: 0 !important;
        }
        
        /* Info items for print - more compact */
        .info-company-grid,
        .info-grid {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .info-item,
        .info-item-details {
            display: block !important;
            background: white !important;
            border: 1px solid #ddd !important;
            border-left: 2px solid #000 !important;
            padding: 6px 8px !important;
            margin: 0 0 5px 0 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            width: 100% !important;
            float: none !important;
        }
        
        .info-item:hover,
        .info-item-details:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        
        .info-label {
            color: #000 !important;
            font-weight: bold !important;
            font-size: 11px !important;
            margin: 0 !important;
            display: inline !important;
            padding: 0 !important;
        }
        
        .info-value {
            color: #000 !important;
            font-size: 12px !important;
            display: inline !important;
            margin-left: 5px !important;
            word-break: break-all !important;
        }
        
        /* Contact Person sections */
        .row > .col-md-6 {
            width: 50% !important;
            float: left !important;
            padding: 0 5px !important;
        }
        
        .row > .col-md-6 span[style*="font-weight: bold"] {
            font-size: 14px !important;
            margin-bottom: 8px !important;
            display: block !important;
        }
        
        /* Footer for print */
        .print-footer {
            display: block !important;
            text-align: center;
            margin-top: 20px !important;
            padding-top: 10px !important;
            border-top: 1px solid #000 !important;
            font-size: 10px !important;
            color: #666 !important;
            page-break-before: avoid !important;
        }
        
        /* Ensure single page with minimal margins */
        @page {
            size: A4 portrait !important;
            margin: 0.5cm !important;
        }
        
        /* Force everything to fit */
        html, body {
            height: auto !important;
            width: 100% !important;
            overflow: hidden !important;
        }
        
        /* Remove any unnecessary spacing */
        .mb-4, .mb-3, .mt-4, .mt-3 {
            margin-bottom: 5px !important;
            margin-top: 5px !important;
        }
        
        /* Compact the contact person layout */
        .info-item-details {
            margin-bottom: 3px !important;
            padding: 4px 6px !important;
        }
        
        /* Make sure text doesn't overflow */
        * {
            max-height: none !important;
            min-height: 0 !important;
        }
    }

    @media (max-width: 768px) {
        .client-header {
            text-align: center;
            padding: 25px 20px;
        }
        
        .client-avatar {
            margin: 0 auto 20px;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="content-wrapper mt-4">
    <!-- Print Header (Hidden by default, shown only when printing) -->
    <div class="print-header" style="display: none;">
        <h1>Aagent Details Report</h1>
        <div class="print-date">Generated on: {{ date('F d, Y h:i A') }}</div>
    </div>

    <div class="client-header d-flex align-items-center" style="position: relative; z-index: 1;">
    <div class="row">
        <div class="col-md-2">
             <div class="client-avatar">
                {{ strtoupper(substr($agent->agent_company_name, 0, 2)) }}
            </div>
        </div>
        <div class="col-md-10">
            <div class="client-info details">
                <h2></h2>
                <div class="d-flex align-items-center gap-3 mt-5">
                    <span class="badge">{{ $agent->agent_company_name ?? 'No Company' }}</span>
                    <span class="badge">{{ $agent->desination ?? 'N/A' }}</span>
                    <span class="badge">{{ $agent->agent_name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>   
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Company Information -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-office-building"></i> Company Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-company-grid">
                        <div class="info-item">
                            <span class="info-label">Company Name:</span>
                            <span class="info-value">{{ $agent->agent_company_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Type Of Business:</span>
                            <span class="info-value">{{ $agent->type_business ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Company Location:</span>
                            <span class="info-value">{{ $agent->company_location ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">MEA Number:</span>
                            <span class="info-value">{{ $agent->mea_number ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Licence Number:</span>
                            <span class="info-value">{{ $agent->licence_number ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">GST Number:</span>
                            <span class="info-value">{{ $agent->gst_number ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Address:</span>
                            <span class="info-value">{{ $agent->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-account-circle"></i> Personal Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6">
                            <div style="font-weight: bold; font-size: 16px; margin-bottom: 10px; color: var(--text-dark);">
                                Contact Person 1
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Aagent Name:</span>
                                <span class="info-value">{{ $agent->agent_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Designation:</span>
                                <span class="info-value">{{ $agent->desination ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Mobile Number:</span>
                                <span class="info-value">{{ $agent->mobile_number ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Alternate Number:</span>
                                <span class="info-value">{{ $agent->alternate_number ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Email ID:</span>
                                <span class="info-value">{{ $agent->email_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="font-weight: bold; font-size: 16px; margin-bottom: 10px; color: var(--text-dark);">
                                Contact Person 2
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Aagent Name:</span>
                                <span class="info-value">{{ $agent->agent_name_two ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Designation:</span>
                                <span class="info-value">{{ $agent->desination_two ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Mobile Number:</span>
                                <span class="info-value">{{ $agent->mobile_number_two ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Alternate Number:</span>
                                <span class="info-value">{{ $agent->alternate_number_two ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item-details">
                                <span class="info-label">Email ID:</span>
                                <span class="info-value">{{ $agent->email_id_two ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
    </div>

    <!-- Print Footer (Hidden by default, shown only when printing) -->
    <div class="print-footer" style="display: none;">
        <div>Agent ID: {{ $agent->id ?? 'N/A' }}</div>
        <div>Page 1 of 1</div>
        <div>© {{ date('Y') }} {{ config('app.name', 'Company Name') }}. All rights reserved.</div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-edit">
            <i class="mdi mdi-pencil"></i> Edit Aagent
        </a>
        <button onclick="printSinglePage()" class="btn btn-print">
            <i class="mdi mdi-printer"></i> Print Details
        </button>
        <a href="{{ route('agents.index') }}" class="btn btn-back">
            <i class="mdi mdi-arrow-left"></i> Back to Aagents
        </a>
    </div>

</div>

<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

<script>
    // Add subtle animations
    document.addEventListener('DOMContentLoaded', function() {
        const infoItems = document.querySelectorAll('.info-item');
        infoItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
        });
    });
    
    // Optimized print function for single page
    function printSinglePage() {
        // Store original styles
        const originalStyles = {
            bodyOverflow: document.body.style.overflow,
            bodyHeight: document.body.style.height,
            htmlOverflow: document.documentElement.style.overflow,
            htmlHeight: document.documentElement.style.height
        };
        
        // Show print header and footer
        document.querySelector('.print-header').style.display = 'block';
        document.querySelector('.print-footer').style.display = 'block';
        
        // Hide action buttons
        const actionButtons = document.querySelector('.action-buttons');
        actionButtons.style.display = 'none';
        
        // Minimize height for print
        document.body.style.overflow = 'visible';
        document.body.style.height = 'auto';
        document.documentElement.style.overflow = 'visible';
        document.documentElement.style.height = 'auto';
        
        // Set print-specific styles
        const printStyles = `
            <style>
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    .content-wrapper, .content-wrapper * {
                        visibility: visible;
                    }
                    .content-wrapper {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: auto;
                        margin: 0;
                        padding: 0;
                    }
                }
            </style>
        `;
        
        // Add print styles
        document.head.insertAdjacentHTML('beforeend', printStyles);
        
        // Trigger print
        window.print();
        
        // Restore original state
        document.querySelector('.print-header').style.display = 'none';
        document.querySelector('.print-footer').style.display = 'none';
        actionButtons.style.display = 'flex';
        
        // Restore body styles
        document.body.style.overflow = originalStyles.bodyOverflow;
        document.body.style.height = originalStyles.bodyHeight;
        document.documentElement.style.overflow = originalStyles.htmlOverflow;
        document.documentElement.style.height = originalStyles.htmlHeight;
        
        // Remove print styles
        const addedStyles = document.querySelector('style[media="print"]:last-of-type');
        if (addedStyles) {
            addedStyles.remove();
        }
    }
</script>