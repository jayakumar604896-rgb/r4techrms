<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<div class="content-wrapper">
    <style>
        @media print {
            .no-print { display: none !important; }
            .content-wrapper { margin: 0 !important; padding: 0 !important; }
            .invoice-container { border: none !important; padding: 0 !important; }
            body { font-size: 12px !important; }
            .invoice-main { page-break-inside: avoid; }
        }
        
        @media screen {
            body { background: #f5f5f5; }
            .invoice-container { 
                max-width: 500px; 
                margin: 0 auto; 
                border: 1px solid #ddd; 
                padding: 20px; 
                background: white;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }
        }
        
        .invoice-main {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            line-height: 1.2;
            font-size: 13px;
        }
        
        /* Company Header - Centered */
        .company-header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .company-address {
            font-size: 12px;
            color: #000;
            margin: 1px 0;
            line-height: 1.2;
        }
        .company-contact {
            font-size: 12px;
            margin: 1px 0;
        }
        .company-gst {
            font-size: 12px;
            font-weight: bold;
            margin: 2px 0 5px 0;
        }
        
        /* Invoice Title - Centered, bigger */
        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 15px 0 10px 0;
            text-transform: uppercase;
        }
        
        /* To Section - Left aligned with label */
        .to-section {
            margin: 15px 0 10px 0;
        }
        .to-label {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        .client-name {
            font-size: 13px;
            font-weight: bold;
            margin: 2px 0;
        }
        .client-address {
            font-size: 12px;
            margin: 1px 0;
            line-height: 1.2;
        }
        .client-attention {
            font-size: 12px;
            margin-top: 5px;
        }
        
        /* Invoice Details - Two columns side by side */
        .details-container {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .left-details {
            width: 40%;
        }
        .right-details {
            width: 50%;
        }
        
        /* Details table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        .details-table td {
            padding: 2px 5px;
            vertical-align: top;
        }
        .detail-label {
            font-weight: bold;
            white-space: nowrap;
        }
        
        /* Note Section - Dashed border */
        .note-section {
            margin: 5px 0;
            padding: 10px;
            font-size: 11px;
            line-height: 1.4;
            text-align: justify;
            border: 1px dashed #000;
        }
        
        /* Main Invoice Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 12px;
            border: 1px solid #000;
            max-width: 718px;
        }
        .invoice-table th {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: left;
            background-color: transparent;
            font-weight: bold;
        }
        .invoice-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            vertical-align: top;
        }
        
        /* Net Amount Section */
        .net-amount-section {
            margin: 5px 0;
            text-align: right;
        }
        .net-amount-row {
            display: inline-block;
            text-align: right;
            min-width: 200px;
        }
        .net-amount-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
            text-align: right;
            padding-right: 10px;
        }
        .net-amount-value {
            display: inline-block;
            width: 80px;
            text-align: right;
        }
        
        /* Amount in Words */
        .amount-words-section {
            margin: 20px 0;
            padding: 8px;
            font-size: 12px;
            border: 1px dashed #000;
        }
        .amount-words-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        /* Bank Details */
        .bank-details-section {
            margin: 5px 0;
            font-size: 11px;
            line-height: 1.2;
        }
        .bank-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .bank-info {
            margin: 2px 0;
        }
        
        /* Remarks Section */
        .remarks-section {
            margin: 5px 0;
            padding: 8px;
            font-size: 11px;
            border: 1px dashed #000;
            background-color: #f9f9f9;
        }
        .remarks-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        /* Footer Signature */
        .footer-section {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }
        .footer-left {
            float: left;
            width: 50%;
            text-align: center;
        }
        .footer-right {
            float: right;
            width: 50%;
            text-align: center;
        }
        .clearfix {
            clear: both;
        }
        .signature-line {
            margin-top: 40px;
            font-weight: bold;
            float:right;
            width:42%
        }
        
        /* Print Actions */
        .print-actions {
            text-align: center;
            margin-top: 80px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>

    <div class="invoice-container">
        <div class="invoice-main">
            @php
                $subtotal = $invoice->total_amount ?? 0;
                $cgstPercentage = 9;
                $sgstPercentage = 9;
                
                $cgstAmount = ($subtotal * $cgstPercentage) / 100;
                $sgstAmount = ($subtotal * $sgstPercentage) / 100;
                $netAmount = $subtotal + $cgstAmount + $sgstAmount;

                $formattedCGST = number_format($cgstAmount, 2);
                $formattedSGST = number_format($sgstAmount, 2);
                $formattedNetAmount = number_format($netAmount, 2);
                // Calculate amounts based on the image (USD currency)
                $unitRate = $invoice->unit_rate ?? 40.0;
                $nos = $invoice->nos ?? 1;
                $subtotal = $unitRate * $nos;
                
                $formattedUnitRate = number_format($unitRate, 1);
                $formattedSubtotal = number_format($subtotal, 1);
                $formattedTotal = number_format($netAmount, 2);
                
                function convertToIndianRupees($amount) {
                    $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

                    if ($amount == 0) {
                        return 'Zero Rupees Only';
                    }

                    // Format to two decimal places
                    $amount = number_format($amount, 2, '.', '');
                    $parts = explode('.', $amount);
                    $rupees = (int)$parts[0];
                    $paise = isset($parts[1]) ? (int)$parts[1] : 0;

                    $words = '';

                    // Convert rupees part
                    if ($rupees > 0) {
                        $words .= convertNumberToIndianWords($rupees, $ones, $tens) . ' Rupee';
                        if ($rupees > 1) {
                            $words .= 's';
                        }
                        $words .= ' ';
                    }

                    // Convert paise part
                    if ($paise > 0) {
                        $words .= 'and ' . convertNumberToIndianWords($paise, $ones, $tens) . ' Paise ';
                    }

                    return $words . 'Only';
                }

                function convertNumberToIndianWords($num, $ones, $tens) {
                    if ($num == 0) {
                        return '';
                    }

                    // Helper for numbers < 100
                    $belowHundred = function($n) use ($ones, $tens) {
                        if ($n < 20) {
                            return $ones[$n];
                        } else {
                            return $tens[floor($n / 10)] . ($n % 10 != 0 ? ' ' . $ones[$n % 10] : '');
                        }
                    };

                    // Indian number system: crores, lakhs, thousands, hundreds
                    $crore = floor($num / 10000000);  // 1 crore = 10 million
                    $num %= 10000000;
                    $lakh = floor($num / 100000);     // 1 lakh = 100 thousand
                    $num %= 100000;
                    $thousand = floor($num / 1000);
                    $num %= 1000;
                    $hundred = floor($num / 100);
                    $remainder = $num % 100;

                    $words = '';

                    if ($crore > 0) {
                        $words .= convertNumberToIndianWords($crore, $ones, $tens) . ' Crore';
                        if ($crore > 1) $words .= 's';
                        $words .= ' ';
                    }
                    if ($lakh > 0) {
                        $words .= convertNumberToIndianWords($lakh, $ones, $tens) . ' Lakh';
                        if ($lakh > 1) $words .= 's';
                        $words .= ' ';
                    }
                    if ($thousand > 0) {
                        $words .= convertNumberToIndianWords($thousand, $ones, $tens) . ' Thousand';
                        if ($thousand > 1) $words .= 's';
                        $words .= ' ';
                    }
                    if ($hundred > 0) {
                        $words .= $ones[$hundred] . ' Hundred ';
                    }
                    if ($remainder > 0) {
                        $words .= $belowHundred($remainder) . ' ';
                    }

                    return trim($words);
                }
                
                $amountInWords = convertToIndianRupees($netAmount);
            @endphp
            
            <!-- Company Header -->
            <div class="company-header">
                <img src="{{asset('assets/images/printheader.png')}}" style="width:100%" />
            </div>
            
            <!-- Invoice Title -->
            <div class="invoice-title remarks-section">TAX INVOICE</div>
            
            <div class="details-container remarks-section">
                <div class="left-details">
                    <div class="to-section">
                        <div class="to-label">To:</div>
                        <div class="client-name">{{ $clients->contact_person ?? '' }}</div>
                        <div class="client-address">{{ $clients->company_location ?? '' }}</div>
                        <div class="client-attention">
                            <strong>{{ $clients->contact_person ?? '' }}</strong><br>
                            {{ $clients->desination ?? '' }}
                        </div>
                    </div>
                </div>
                
                <div class="right-details">
                    <table class="details-table">
                        <tr>
                            <td class="detail-label">Invoice No:</td>
                            <td>{{ $invoice->invoice_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">Invoice Date:</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date ?? now())->format('d-M-y') }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">GST No.:</td>
                            <td>{{ $invoice->lic_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">PAN No.:</td>
                            <td>{{ $invoice->pan_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">TAN No:</td>
                            <td>{{ $invoice->tan ?? '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Note Section -->
            <div class="note-section">
                We are pleased to present our invoice for the Management services, consultancy, manpower / Materials support rendered by us as per the details given below. We will be thankful if you could kindly arrange to send a Cheque (QD / T/T) e-transfer and MT by MOC / Pay Order for the amount mentioned below, in favour of <strong>M/s.Paswi Manpower Consultants, Poonur, Chennai-116.</strong> (Pls. See our Banker details below.)
            </div>
            
            <!-- Main Invoice Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">S.No</th>
                        <th style="width: 50%;">Particulars</th>
                        <th style="width: 10%; text-align: center;">Nos</th>
                        <th style="width: 15%; text-align: right;">Unit rate USD</th>
                        <th style="width: 15%; text-align: right;">Total Amount USD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td>
                            {!! $invoice->particular ?? '' !!}
                        </td>
                        <td style="text-align: center;">{{ $nos }}</td>
                        <td style="text-align: right;">{{ $formattedUnitRate }}</td>
                        <td style="text-align: right;">{{ $formattedSubtotal }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" style="text-align: right;"><b>CGST 9%</b></td>
                        <td style="text-align: right;">{{ $formattedCGST ?? '' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" style="text-align: right;""><b>SGST 9%</b></td>
                        <td style="text-align: right;">{{ $formattedSGST ?? '' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" style="text-align: right;"><b>IGST 18%</b></td>
                        <td style="text-align: right;">{{ $invoice->igst ?? '' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" style="text-align: right;"><b>Net Amount</b></td>
                        <td style="text-align: right;">{{ $formattedTotal ?? '' }}</td>
                    </tr>
                    <tr>
                    <td colspan="5" style="text-align: center;"><b>Amount in Words:</b> {{ $amountInWords }} </td>
                    </tr>

                </tbody>
            </table>
            
            
            <!-- Net Amount-->
            <!--<div class="net-amount-section">
                <div class="net-amount-row">
                    <div class="net-amount-label">Net Amount</div>
                    <div class="net-amount-value"></div>
                </div>
            </div> -->
            
            <!-- Amount in Words -->
             <!--<div class="amount-words-section">
                <div class="amount-words-title"><span>Amount in Words:</span> {{ $amountInWords }}</div>
                <div></div>
            </div>-->
            
            <!-- Bank Details -->
            <div class="bank-details-section ">
                <div class="remarks-section">
                    <div class="bank-title">Pay in Favour of M/s. PASWI MANPOWER CONSULTANTS, Sarki. KA/NAI/ARA BANK LTD.</div>
                    <div class="bank-info">Current A/C No. 1522-0031-0030-3901, IFSC CODE: KA/BS03300152</div>
                    <div class="bank-info">Seal Code of Karmeshwar, Chennai, INDIA.</div>
                </div>
                <div class="remarks-section">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{asset('/uploads/bankimages/bank 01.png')}}"/>
                        </div>
                        <div class="col-md-9">
                            <div>
                                <div class="bank-title">Bankers A/c. Details: Credit to A/c. No : 36252322 of Karnataka bank Ltd.,</div>
                                <div class="bank-info">International Division , Mumbai , with citi bank NA , 111 Wall Street, New York, NY 10043.</div>
                                <div class="bank-info">Swift Code of Citi Bank , New York - CITIUS33 ,  Swift Code Of Karnataka Bank , Chennai  INDIA is KARBINBBMDS , Routing No : 0210089. Currency for Transfer in RS.</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- Remarks -->
            <div class="remarks-section">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{asset('/uploads/bankimages/bank 02.png')}}"/>
                    </div>
                    <div class="col-md-9">
                        <div class="remarks-title">PASWI MANPOWER CONSULTANT:</div>
                        <div>IDFC FIRST BANK, Cur. A/C No: 51412200550, IFSC Code- IDFB0081832,   </div>
                        <div>Swift Code :  IDFBINBBMUM     Mount Road branch, Chennai, INDIA.  </div>
                        <div>UPID: paswimanpowerconsult@idfcbank   </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer-section">
                <div class="footer-left">
                    <strong>For Party Use</strong>
                </div>
                <div class="footer-right">
                    <strong>for Paswi Manpower Consultant</strong>
                </div>
                <div class="clearfix"></div>
                
                <div class="signature-line">
                    <div>Multibank, V</div>
                    <div>Managing Partner</div>
                </div>
            </div>
        </div>
        
        <div class="no-print print-actions">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="mdi mdi-printer"></i> Print Invoice
            </button>
            <a href="{{ route('tax-invoice-reports.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        // Auto-print if needed
        // setTimeout(function() {
        //     window.print();
        // }, 1000);
    };
</script>
