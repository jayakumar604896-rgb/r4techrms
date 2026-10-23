<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANPOWER DEMAND FORM - {{ $demand->demand_ref_no ?? 'N/A' }}</title>
    <style>
        /* EXACT MATCH TO YOUR WORD DOCUMENT STYLING */
        @media print {
            @page {
                margin: 0.25in;
                size: A4 portrait;
            }
            
            body {
                font-family: 'Arial', sans-serif;
                font-size: 11pt;
                line-height: 1.3;
                color: black;
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-only {
                display: block !important;
            }
            
            /* Force table borders to print */
            table, td, th {
                border-collapse: collapse !important;
                border: 1px solid black !important;
            }
            
            /* Ensure all backgrounds print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        /* Base styling - EXACT Word document layout */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: black;
            background: white;
            margin: 0;
            padding: 20px;
        }
        
        .document-container {
            width: 100%;
            max-width: 8.5in;
            margin: 0 auto;
            position: relative;
        }
        
        /* Header styling matching Word doc */
        .header-section {
            margin-bottom: 20px;
            position: relative;
        }
        
        .demand-ref-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid black;
        }
        
        .demand-ref-text {
            font-size: 11pt;
            font-weight: normal;
        }
        
        .demand-ref-value {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            min-width: 200px;
            text-align: left;
            padding-left: 10px;
        }
        
        .logo-placeholder {
            width: 1.15625in;
            height: 0.9055555555555556in;
            border: 1px dashed #999;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9pt;
            color: #666;
            float: right;
            margin-left: 10px;
        }
        
        .form-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0;
            padding: 5px;
            border: 2px solid black;
            background-color: #f0f0f0;
        }
        
        /* Modified date */
        .modified-date {
            text-align: right;
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }
        
        /* Field rows styling */
        .field-row {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .field-label {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 3px;
            display: block;
        }
        
        .field-underline {
             /*border-bottom: 1px solid black;*/
            padding: 2px 0;
            min-height: 20px;
            position: relative;
        }
        
        .field-value {
            font-size: 11pt;
            display: inline-block;
            min-width: 50px;
            padding: 0 5px;
        }
        
        .field-empty {
            color: #999;
            font-style: italic;
        }
        
        /* Multi-column layouts */
        .two-columns {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        
        .column {
            flex: 1;
        }
        .discolumn
        {
            display: flex;
            gap: 10px;
            margin-top:10px;
        }
         
        .three-columns {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        
        /* Checkbox sections */
        .checkbox-section {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
        }
        
        .checkbox-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 8px;
            margin-top: 10px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .checkbox-box {
            width: 14px;
            height: 14px;
            border: 1px solid black;
            display: inline-block;
            position: relative;
        }
        
        .checkbox-box.checked::after {
            content: '✓';
            position: absolute;
            top: -2px;
            left: 2px;
            font-size: 12px;
        }
        
        .checkbox-label {
            font-size: 10pt;
        }
        
        /* Keywords sections */
        .keywords-section {
            margin: 20px 0;
            border: 1px solid black;
            padding: 10px;
        }
        
        .keywords-header {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .keywords-columns {
            display: flex;
            gap: 30px;
        }
        
        .keywords-column {
            flex: 1;
        }
        
        .keyword-row {
            margin-bottom: 10px;
        }
        
        .keyword-label {
            font-size: 10pt;
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        
        .keyword-value {
            border-bottom: 1px solid black;
            padding: 2px 5px;
            min-width: 200px;
            display: inline-block;
            font-size: 10pt;
        }
        
        /* Table styling */
        .cvs-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .cvs-table td, .cvs-table th {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            font-size: 10pt;
        }
        
        .cvs-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        /* Approval section */
        .approval-section {
            margin-top: 30px;
            border-top: 2px solid black;
            padding-top: 15px;
        }
        
        .approval-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            text-align: center;
        }
        
        .approval-item {
            padding: 5px;
        }
        
        .approval-title {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 25px;
            display: block;
        }
        
        .approval-line {
            border-top: 1px solid black;
            width: 80%;
            margin: 0 auto;
            padding-top: 5px;
        }
        
        .approval-subtext {
            font-size: 8pt;
            color: #666;
            display: block;
            margin-top: 5px;
        }
        
        /* Footer */
        .footer-note {
            font-size: 9pt;
            color: #666;
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        /* Print controls */
        .print-controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            border: 1px solid #ccc;
        }
        
        .btn {
            padding: 8px 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
            font-size: 11pt;
            margin: 0 5px;
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
        
        /* Responsive for screen */
        @media screen {
            .print-only {
                display: none;
            }
        }
        
        /* Helper classes */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-bold {
            font-weight: bold;
        }
        
        .mt-10 {
            margin-top: 10px;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Print Controls (Visible only on screen) -->
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
                <span class="demand-ref-text">Demand Ref No.:</span>
                <span class="demand-ref-value">{{ $demand->demand_ref_no ?? '' }}</span>
                <div class="logo-placeholder">
                    [Company Logo]
                </div>
            </div>
            
            <div class="demand-ref-line">
                <span class="demand-ref-text">Demand open Date: {{ $demand->demand_open_date ? $demand->demand_open_date->format('d/m/Y') : '' }}</span>
                <span class="demand-ref-text">Demand Closed Date: {{ $demand->demand_closed_date ? $demand->demand_closed_date->format('d/m/Y') : '' }}</span>
            </div>
            
            <div class="modified-date print-only">
                Printed on: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
        
        <!-- Main Title -->
        <div class="form-title">MANPOWER DEMAND</div>
        
        <!-- Basic Information -->
        <div class="field-row discolumn">
            <div class="field-label">Recruiter Name:</div>
            <div class="field-underline">
                <span class="field-value">{{ $demand->recruiter_name ?? '' }}</span>
            </div>
        </div>
        
        <div class="field-row discolumn">
            <div class="field-label">Post:</div>
            <div class="field-underline">
                <span class="field-value">{{ $demand->designation_offered ?? '' }}</span>
            </div>
        </div>
        
        <div class="field-row discolumn">
            <div class="field-label">Client / Agent / Source:</div>
            <div class="field-underline">
                <span class="field-value">M/s {{ $demand->client_agent_source ?? '' }}</span>
            </div>
        </div>
        
        <!-- Three Column Section -->
        <div class="three-columns">
            <div class="column discolumn">
                <div class="field-label">Qualification</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->qualification ?? '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Exp.</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->experience ?? '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Job Location</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->job_location ?? '' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Mode of Interview, Food, Accom & Transport -->
        <div class="three-columns">
            <div class="column discolumn">
                <div class="field-label">Mode of Interview:</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->mode_of_interview ?? '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Food</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->food ?? '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Accom & Transport</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->accommodation_transport ?? '' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Salary, Working Hours, Overtime -->
        <div class="three-columns">
            <div class="column discolumn">
                <div class="field-label">Salary:</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->salary ?? '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Workg Hrs.</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->working_hours ?? '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Overtime</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->overtime ?? '' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Interview Date and Location -->
        <div class="two-columns">
            <div class="column discolumn">
                <div class="field-label">Int. Date:</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->interview_date ? $demand->interview_date->format('d/m/Y') : '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Int. Location</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->interview_location ?? '' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Service Charge and Certificate Requirements -->
        <div class="two-columns">
            <div class="column discolumn">
                <div class="field-label">Ser. Charge:</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->service_charge ?? '' }}</span>
                </div>
            </div>
            <div class="column discolumn">
                <div class="field-label">Cert / spl. Rqmt:</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->certificate_special_requirements ?? '' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Mode of Selection -->
        <!-- Mode of Selection - Match Database Format -->
        <div class="checkbox-section">
            <div class="checkbox-title">Mode of Selection :</div>
            <div class="checkbox-grid">
                @php
                    // Use the exact same labels as in your database
                    $selectionModes = ['CV Selection', 'Direct Interview', 'Telephone Interview', 'Video Call'];
                @endphp
                @foreach($selectionModes as $mode)
                <div class="checkbox-item">
                    <div class="checkbox-box 
                        @if($demand->mode_of_selection && in_array($mode, $demand->mode_of_selection)) 
                            checked 
                        @endif">
                    </div>
                    <span class="checkbox-label">{{ $mode }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Type of Hiring -->
        <!-- Type of Hiring - Fixed Version -->
<div class="checkbox-section">
    <div class="checkbox-title">TICK THE TYPE OF HIRING:</div>
    <div class="checkbox-grid">
        @php
            // Display labels for print (from your Word document)
            $displayHiringTypes = [
                'BULK HIRING', 
                'LABOUR & TECHNICIAN CADRE', 
                'EXECUTIVE STAFFING', 
                'CONTRACT STAFFING', 
                'GLOBAL HIRING'
            ];
            
            // Mapping from display labels to possible database values
            $hiringMapping = [
                'BULK HIRING' => ['BULK HIRING', 'Bulk Hiring', 'bulk hiring', 'Bulk hiring'],
                'LABOUR & TECHNICIAN CADRE' => ['LABOUR & TECHNICIAN CADRE', 'Labour & Technician Cadre', 'labour & technician cadre', 'Labor & Technician Cadre'],
                'EXECUTIVE STAFFING' => ['EXECUTIVE STAFFING', 'Executive Staffing', 'executive staffing'],
                'CONTRACT STAFFING' => ['CONTRACT STAFFING', 'Contract Staffing', 'contract staffing'],
                'GLOBAL HIRING' => ['GLOBAL HIRING', 'Global Hiring', 'global hiring']
            ];
            
            // Check if any variation is selected
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
        
        <!-- Keywords Section -->
        <div class="keywords-section">
            <div class="keywords-header">A. Main Keywords to Check &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; B. Secondary Keywords to Check</div>
            
            <div class="keywords-columns">
                <!-- Column A: Main Keywords -->
                <div class="keywords-column">
                    <div class="keyword-row">
                        <span class="keyword-label">Design:</span>
                        <span class="keyword-value">{{ $demand->main_keyword_design ?? '' }}</span>
                    </div>
                    <div class="keyword-row">
                        <span class="keyword-label">Qln :</span>
                        <span class="keyword-value">{{ $demand->main_keyword_qualification ?? '' }}</span>
                    </div>
                    <div class="keyword-row">
                        <span class="keyword-label">Exp :</span>
                        <span class="keyword-value">{{ $demand->main_keyword_experience ?? '' }}</span>
                    </div>
                    <div class="keyword-row">
                        <span class="keyword-label">Spl. Req:</span>
                        <span class="keyword-value">{{ $demand->main_keyword_special_requirements ?? '' }}</span>
                    </div>
                </div>
                
                <!-- Column B: Secondary Keywords -->
                <div class="keywords-column">
                    <div class="keyword-row">
                        <span class="keyword-label">1. Notice</span>
                        <span class="keyword-value">{{ $demand->secondary_keyword_notice ?? '' }}</span>
                    </div>
                    <div class="keyword-row">
                        <span class="keyword-label">2. Sal. Budget</span>
                        <span class="keyword-value">{{ $demand->secondary_keyword_salary_budget ?? '' }}</span>
                    </div>
                    <div class="keyword-row">
                        <span class="keyword-label">3.</span>
                        <span class="keyword-value">{{ $demand->secondary_keyword_additional_3 ?? '' }}</span>
                    </div>
                    <div class="keyword-row">
                        <span class="keyword-label">4.</span>
                        <span class="keyword-value">{{ $demand->secondary_keyword_additional_4 ?? '' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Sector/Industry and Candidate Location -->
            <div class="two-columns mt-20">
                <div class="column">
                    <div class="field-label">Sector / Industry :</div>
                    <div class="field-underline">
                        <span class="field-value">{{ $demand->industry ?? '' }}</span>
                    </div>
                </div>
                <div class="column">
                    <div class="field-label">Cand. Location (Mention Area if need be) Eg: Pune, Gujarat, TN :</div>
                    <div class="field-underline">
                        <span class="field-value">{{ $demand->candidate_location ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Positions and Advertisement -->
        <div class="two-columns mt-20">
            <div class="column">
                <div class="field-label">No of Positions:</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->no_of_positions ?? '' }}</span>
                </div>
            </div>
            <div class="column">
                <div class="field-label">Reqd. CVs:</div>
                <div class="field-underline">
                    <span class="field-value">{{ $demand->required_cvs ?? '' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Advertisement Portals -->
        <div class="checkbox-section mt-10">
            <div class="checkbox-title">Advertisement Portals:</div>
            <div class="checkbox-grid">
                @php
                    $portals = ['Social Media', 'Print Media', 'Local Job Portals', 'Online Portals'];
                @endphp
                @foreach($portals as $portal)
                <div class="checkbox-item">
                    <div class="checkbox-box @if($demand->advertisement_portals && in_array($portal, $demand->advertisement_portals)) checked @endif"></div>
                    <span class="checkbox-label">{{ $portal }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Approval Section -->
        <div class="approval-section">
            <div class="text-center text-bold mb-10">Demand Approval Media & Adv</div>
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
                    <span class="approval-title">Executive</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
            </div>
        </div>
        
        <!-- CVS Dispatch Summary Table -->
        <div class="mt-20">
            <div class="text-center text-bold mb-10">CVS Dispatch Summary</div>
            <table class="cvs-table">
                <thead>
                    <tr>
                        <th>Tot Cv Submitted</th>
                        <th>Selection / Rejection<br>(Name of sel. Candidate)</th>
                        <th>Package fixed & accepted</th>
                        <th>DOJ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td height="50"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td height="50"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Final Approval Section -->
        <div class="approval-section">
            <div class="approval-grid">
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
                <div class="approval-item">
                    <span class="approval-title">Received and executed by</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
                <div class="approval-item">
                    <span class="approval-title">Viewed & Verified Approved</span>
                    <div class="approval-line"></div>
                    <span class="approval-subtext">Signature & Date</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer-note print-only">
            Document ID: DEM-{{ $demand->id ?? '00000' }} | Printed: {{ now()->format('d/m/Y H:i:s') }} | Page 1 of 1
        </div>
    </div>

    <script>
        // Auto-trigger print dialog when page loads
        window.onload = function() {
            // Optional: Auto-open print dialog
            // Uncomment the line below if you want automatic printing
            // window.print();
            
            // Set focus for better printing
            window.focus();
        };
        
        // Close window after print (if opened in new window)
        window.onafterprint = function() {
            // Optional: Close window after printing
            // if (window.opener) {
            //     setTimeout(function() {
            //         window.close();
            //     }, 500);
            // }
        };
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P or Cmd+P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // Escape to close
            if (e.key === 'Escape') {
                window.close();
            }
        });
    </script>
</body>
</html>