<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANPOWER DEMAND FORM - {{ $demand->demand_ref_no ?? 'N/A' }}</title>

    <style>
        /* COMPACT PRINT STYLING FOR SINGLE PAGE */
        @media print {
            @page {
                margin: 0.15in;
                size: A4 portrait;
            }
            
            body {
                font-family: 'Arial', sans-serif;
                font-size: 9pt;
                line-height: 1.1;
                color: black;
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
                border: 3px solid #000;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-only {
                display: block !important;
            }
            
            table, td, th {
                border-collapse: collapse !important;
                border: 1px solid black !important;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        /* Base styling - COMPACT layout */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.1;
            color: black;
            background: white;
            margin: 0;
            padding: 10px;
        }
        
        .document-container {
            width: 100%;
            max-width: 8.5in;
            margin: 0 auto;
            position: relative;
        }
        
        /* Header styling */
        .header-section {
            margin-bottom: 5px;
            position: relative;
        }
        
        .demand-ref-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 3px;
            border-bottom: 1px solid black;
        }
        
        .demand-ref-text {
            font-size: 9pt;
            font-weight: normal;
        }
        
        .demand-ref-value {
            font-size: 9pt;
            font-weight: bold;
            text-decoration: underline;
            min-width: 150px;
            text-align: left;
            padding-left: 5px;
        }
        
        .logo-placeholder {
            width: 0.9in;
            height: 0.7in;
            border: 1px dashed #999;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #666;
            float: right;
            margin-left: 5px;
        }
        
        .form-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 6px 0;
            padding: 3px;
            border: 1px solid black;
            background-color: #f0f0f0;
        }
        
        .modified-date {
            text-align: right;
            font-size: 8pt;
            color: #666;
            margin-bottom: 3px;
        }
        
        /* TWO COLUMN GRID LAYOUT */
        .two-col-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px 15px;
            margin-bottom: 4px;
        }
        
        .field-item {
            display: flex;
            align-items: baseline;
            padding: 2px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .field-item .label {
            font-weight: bold;
            font-size: 9pt;
            min-width: 85px;
            flex-shrink: 0;
            color: #333;
        }
        
        .field-item .value {
            font-size: 9pt;
            padding-left: 5px;
            flex: 1;
            min-height: 18px;
            border-bottom: 1px solid transparent;
        }
        
        .field-item .value.underline {
            border-bottom: 1px solid #000;
        }
        
        /* Full width fields */
        .full-width {
            grid-column: 1 / -1;
        }
        
        /* Checkbox sections */
        .checkbox-section {
            margin: 6px 0;
            padding: 5px 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        
        .checkbox-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }
        
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 3px 8px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .checkbox-box {
            width: 12px;
            height: 12px;
            border: 1px solid black;
            display: inline-block;
            position: relative;
            flex-shrink: 0;
        }
        
        .checkbox-box.checked::after {
            content: '✓';
            position: absolute;
            top: -2px;
            left: 1px;
            font-size: 10px;
        }
        
        .checkbox-label {
            font-size: 8.5pt;
        }
        
        /* Positions row */
        .positions-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 4px 0;
            padding: 4px 0;
        }
        
        .positions-row .field-item {
            border-bottom: none;
        }
        
        .positions-row .field-item .value {
            border-bottom: 1px solid #000;
            min-width: 80px;
        }
        
        /* CVS Table */
        .cvs-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 8pt;
        }
        
        .cvs-table td, .cvs-table th {
            border: 1px solid black;
            padding: 3px 5px;
            text-align: center;
        }
        
        .cvs-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .cvs-table td {
            height: 22px;
        }
        
        /* Approval section */
        .approval-section {
            margin-top: 12px;
            border-top: 1px solid black;
            padding-top: 6px;
        }
        
        .approval-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
            text-align: center;
            margin-top: 50px;
        }
        
        .approval-item {
            padding: 2px;
        }
        
        .approval-title {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 12px;
            display: block;
        }
        
        .approval-line {
            border-top: 1px solid black;
            width: 70%;
            margin: 0 auto;
            padding-top: 2px;
        }
        
        .approval-subtext {
            font-size: 7pt;
            color: #666;
            display: block;
            margin-top: 2px;
        }
        
        .footer-note {
            font-size: 8pt;
            color: #666;
            text-align: center;
            margin-top: 8px;
            padding-top: 4px;
            border-top: 1px solid #ddd;
        }
        
        /* Print controls */
        .print-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            border: 1px solid #ccc;
        }
        
        .btn {
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
            font-size: 10pt;
            margin: 0 3px;
            background: white;
        }
        
        .btn-print {
            background: #4CAF50;
            color: white;
            border-color: #45a049;
        }
        
        .btn-back {
            background: #f5f5f5;
        }
        
        .btn-close {
            background: #f44336;
            color: white;
            border-color: #d32f2f;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-bold {
            font-weight: bold;
        }
        
        .mt-5 { margin-top: 5px; }
        .mt-10 { margin-top: 10px; }
        .mb-5 { margin-bottom: 5px; }
        .mb-10 { margin-bottom: 10px; }
        
        .print-only {
            display: none;
        }
        
        @media screen {
            .print-only {
                display: none;
            }
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .two-col-grid {
                grid-template-columns: 1fr;
            }
            .positions-row {
                grid-template-columns: 1fr;
            }
            .approval-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .checkbox-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls no-print">
        <button class="btn btn-print" onclick="window.print()">
            🖨️ Print Now
        </button>
        <button class="btn btn-back" onclick="window.history.back()">
            ← Back
        </button>
        <button class="btn btn-close" onclick="window.close()">
            ✕ Close
        </button>
    </div>
    
    <div class="document-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="demand-ref-line">
                <span class="demand-ref-text">Demand Ref No.:{{ $demand->demand_ref_no ?? '' }}</span>
				<img src="https://rms.r4rtechnologies.in/assets/images/rms_logo.png" alt="logo" class="logo-dark" style='width:200px'>	
                <!-- <div class="logo-placeholder">
                    [Company Logo]
                </div> -->
            </div>
            <div class="form-title">MANPOWER DEMAND</div>
            
            <div class="demand-ref-line">
                <span class="demand-ref-text">Open: {{ $demand->demand_open_date ? $demand->demand_open_date->format('d/m/Y') : '' }}</span>
                <span class="demand-ref-text">Closed: {{ $demand->demand_closed_date ? $demand->demand_closed_date->format('d/m/Y') : '' }}</span>
            </div>
            
            <div class="modified-date print-only">
                Printed: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
        
        <!-- ============================================ -->
        <!-- TWO COLUMN GRID - All fields in 2 columns -->
        <!-- ============================================ -->
        <div class="two-col-grid">
            <!-- Row 1: Recruiter | Post -->
            <div class="field-item">
                <span class="label">Recruiter:</span>
                <span class="value underline">{{ $demand->recruiter_name ?? '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Post:</span>
                <span class="value underline">{{ $demand->designation_offered ?? '' }}</span>
            </div>
            
            <!-- Row 2: Client/Agent | Qualification -->
            <div class="field-item">
                <span class="label">Client / Agent:</span>
                <span class="value underline">M/s {{ $demand->client_agent_source ?? '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Qualification:</span>
                <span class="value underline">{{ $demand->qualification ?? '' }}</span>
            </div>
            
            <!-- Row 3: Experience | Salary -->
            <div class="field-item">
                <span class="label">Experience:</span>
                <span class="value underline">{{ $demand->experience ?? '' }} Years</span>
            </div>
            <div class="field-item">
                <span class="label">Salary:</span>
                <span class="value underline">{{ $demand->salary ?? '' }}</span>
            </div>
            
            <!-- Row 4: Work Hrs | Overtime -->
            <div class="field-item">
                <span class="label">Work Hrs:</span>
                <span class="value underline">{{ $demand->working_hours ?? '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Overtime:</span>
                <span class="value underline">{{ $demand->overtime ?? '' }}</span>
            </div>
            
            <!-- Row 5: Interview | Food -->
            <div class="field-item">
                <span class="label">Interview:</span>
                <span class="value underline">{{ $demand->mode_of_interview ?? '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Food:</span>
                <span class="value underline">{{ $demand->food ?? '' }}</span>
            </div>
            
            <!-- Row 6: Int. Date | Int. Location -->
            <div class="field-item">
                <span class="label">Int. Date:</span>
                <span class="value underline">{{ $demand->interview_date ? $demand->interview_date->format('d/m/Y') : '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Int. Location:</span>
                <span class="value underline">{{ $demand->interview_location ?? '' }}</span>
            </div>
            
            <!-- Row 7: Job Location | Accom & Trans -->
            <div class="field-item">
                <span class="label">Job Location:</span>
                <span class="value underline">{{ $demand->job_location ?? '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Accom & Trans:</span>
                <span class="value underline">{{ $demand->accommodation_transport ?? '' }}</span>
            </div>
            
            <!-- Row 8: Service Charge | Cert Requirements (Full Width) -->
            <div class="field-item">
                <span class="label">Service Charge:</span>
                <span class="value underline">{{ $demand->service_charge ?? '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Cert / Spl Req:</span>
                <span class="value underline">{{ $demand->certificate_special_requirements ?? '' }}</span>
            </div>
        </div>
        <!-- ============================================ -->
        <!-- POSITIONS & REQD CVS - Two columns -->
        <!-- ============================================ -->
        <div class="positions-row">
            <div class="field-item">
                <span class="label">Positions:</span>
                <span class="value underline">{{ $demand->no_of_positions ?? '' }}</span>
            </div>
            <div class="field-item">
                <span class="label">Reqd. CVs:</span>
                <span class="value underline">{{ $demand->required_cvs ?? '' }}</span>
            </div>
			<div class="field-item">
                <span class="label">Sector / Industry:</span>
                <span class="value underline">{{ $demand->industry ?? '' }}</span>
            </div>
        </div>
        <!-- ============================================ -->
        <!-- TYPE OF HIRING - Checkboxes -->
        <!-- ============================================ -->
        <div class="checkbox-section">
            <div class="checkbox-title">Type of Hiring:</div>
            <div class="checkbox-grid">
                @php
                    $displayHiringTypes = [
                        'BULK HIRING', 
                        'LABOUR & TECHNICIAN', 
                        'EXECUTIVE STAFFING', 
                        'CONTRACT STAFFING', 
                        'GLOBAL HIRING'
                    ];
                    
                    $hiringMapping = [
                        'BULK HIRING' => ['BULK HIRING', 'Bulk Hiring', 'bulk hiring', 'Bulk hiring'],
                        'LABOUR & TECHNICIAN' => ['LABOUR & TECHNICIAN CADRE', 'Labour & Technician Cadre', 'labour & technician cadre', 'Labor & Technician Cadre'],
                        'EXECUTIVE STAFFING' => ['EXECUTIVE STAFFING', 'Executive Staffing', 'executive staffing'],
                        'CONTRACT STAFFING' => ['CONTRACT STAFFING', 'Contract Staffing', 'contract staffing'],
                        'GLOBAL HIRING' => ['GLOBAL HIRING', 'Global Hiring', 'global hiring']
                    ];
                    
                    function isHiringChecked($demand, $displayType, $mapping) {
                        if (!$demand->type_of_hiring || !is_array($demand->type_of_hiring)) {
                            return false;
                        }
                        
                        $variations = $mapping[$displayType] ?? [$displayType];
                        
                        foreach ($variations as $variation) {
                            foreach ($demand->type_of_hiring as $selected) {
                                if (strcasecmp(trim($selected), trim($variation)) === 0) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    }
                @endphp
                
                @foreach($displayHiringTypes as $type)
                <div class="checkbox-item">
                    <div class="checkbox-box @if(isHiringChecked($demand, $type, $hiringMapping)) checked @endif"></div>
                    <span class="checkbox-label">{{ $type }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        
        
        <!-- ============================================ -->
        <!-- ADVERTISEMENT PORTALS - Checkboxes -->
        <!-- ============================================ -->
        <div class="checkbox-section">
            <div class="checkbox-title">Advertisement Portals:</div>
            <div class="checkbox-grid">
                @php
                    $portals = ['Social Media', 'Print Media', 'Local Portals', 'Online Portals'];
                @endphp
                @foreach($portals as $portal)
                <div class="checkbox-item">
                    <div class="checkbox-box @if($demand->advertisement_portals && in_array($portal, $demand->advertisement_portals)) checked @endif"></div>
                    <span class="checkbox-label">{{ $portal }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- ============================================ -->
        <!-- CVS DISPATCH SUMMARY TABLE -->
        <!-- ============================================ -->
        <div class="mt-5">
            <div class="text-center text-bold mb-5">CVS Dispatch Summary</div>
            <table class="cvs-table">
                <thead>
                    <tr>
                        <th style="width:25%">CV Submitted</th>
                        <th style="width:25%">Selection / Rejection</th>
                        <th style="width:25%">Package Accepted</th>
                        <th style="width:25%">DOJ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="text-bold">
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- ============================================ -->
        <!-- APPROVAL SECTION -->
        <!-- ============================================ -->
        <div class="approval-section">
            <div class="approval-grid">
                <div class="approval-item">
                    <span class="approval-title">HRM / MD</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
                <div class="approval-item">
                    <span class="approval-title">Sales & Marketing</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
                <div class="approval-item">
                    <span class="approval-title">Recruiter</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
                <div class="approval-item">
                    <span class="approval-title">Manager-HR</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
                <div class="approval-item">
                    <span class="approval-title">MD</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer-note print-only">
            Document ID: DEM-{{ $demand->id ?? '00000' }} | Printed: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <script>
        window.onload = function() {
            window.focus();
        };
        
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            if (e.key === 'Escape') {
                window.close();
            }
        });
    </script>
</body>
</html>