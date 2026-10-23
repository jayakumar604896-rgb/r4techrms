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
            width: 60%;
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
            font-size:13px !important
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
                // Calculate amounts
                $unitRate = $invoice->unit_rate ?? 40.0;
                $nos = $invoice->nos ?? 1;
                $subtotal = $unitRate * $nos;

                $cgstPercentage = 9;
                $sgstPercentage = 9;

                $cgstAmount = ($subtotal * $cgstPercentage) / 100;
                $sgstAmount = ($subtotal * $sgstPercentage) / 100;
                $netAmount = $subtotal + $cgstAmount + $sgstAmount;

                $formattedUnitRate = number_format($unitRate, 1);
                $formattedSubtotal = number_format($subtotal, 1);
                $formattedCGST = number_format($cgstAmount, 2);
                $formattedSGST = number_format($sgstAmount, 2);
                $formattedNetAmount = number_format($netAmount, 2);

                /**
                * Convert a number to words using US English (thousands, millions, billions)
                */
                function numberToWordsUs($num)
                {
                    $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
                    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

                    if ($num == 0) {
                        return 'Zero';
                    }

                    $belowHundred = function($n) use ($ones, $tens) {
                        if ($n < 20) {
                            return $ones[$n];
                        }
                        return $tens[intdiv($n, 10)] . ($n % 10 ? ' ' . $ones[$n % 10] : '');
                    };

                    // Split into billions, millions, thousands, remainder
                    $billions = intdiv($num, 1000000000);
                    $num %= 1000000000;
                    $millions = intdiv($num, 1000000);
                    $num %= 1000000;
                    $thousands = intdiv($num, 1000);
                    $remainder = $num % 1000;

                    $words = '';

                    if ($billions) {
                        $words .= numberToWordsUs($billions) . ' Billion ';
                    }
                    if ($millions) {
                        $words .= numberToWordsUs($millions) . ' Million ';
                    }
                    if ($thousands) {
                        $words .= numberToWordsUs($thousands) . ' Thousand ';
                    }
                    if ($remainder) {
                        $hundreds = intdiv($remainder, 100);
                        $tensPart = $remainder % 100;
                        if ($hundreds) {
                            $words .= $ones[$hundreds] . ' Hundred ';
                        }
                        if ($tensPart) {
                            $words .= $belowHundred($tensPart) . ' ';
                        }
                    }

                    return trim($words);
                }

                /**
                * Convert a USD amount (dollars.cents) to words
                */
                function convertToUSD($amount)
                {
                    // Ensure two decimal places
                    $amount = number_format($amount, 2, '.', '');
                    $parts = explode('.', $amount);
                    $dollars = (int)$parts[0];
                    $cents = (int)$parts[1];

                    $words = '';

                    if ($dollars > 0) {
                        $words .= numberToWordsUs($dollars) . ' US Dollar';
                        if ($dollars > 1) $words .= 's';
                    }

                    if ($cents > 0) {
                        if ($dollars > 0) $words .= ' and ';
                        $words .= numberToWordsUs($cents) . ' Cent';
                        if ($cents > 1) $words .= 's';
                    }

                    return $words . ' Only';
                }

                $amountInWords = convertToUSD($formattedSubtotal);
            @endphp
            
            <!-- Company Header -->
            <div class="company-header">
                <img src="{{asset('assets/images/printheader.png')}}" style="width:100%" />
            </div>
            
            <!-- Invoice Title -->
            <div class="invoice-title remarks-section">SALE Invoice</div>
            
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
                            <td class="detail-label">Date:</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date ?? now())->format('d-M-y') }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">PAN No.:</td>
                            <td>{{ $invoice->pan_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="detail-label">LIC No:</td>
                            <td>B-652/CHENNAI/</td>
                        </tr>
                           
                        <tr>
                        <td></td>
                         <td>PART/1000+/5/7399/2006</td>
                        </tr>
                        <!--<tr class="col-span-2">
                            <td class="detail-label">{{ $invoice->tan ?? '' }}</td>
                        </tr>-->
                        <!--<tr>
                            <td class="detail-label">HSN code:</td>
                            <td>998519</td>
                        </tr>-->
                    </table>
                </div>
            </div>
            
            <!-- Note Section -->
            <div class="note-section">
                Please find our invoice for HR Services / Management Services / Manpower Support / Engg * Maintenance / Business Advisory / Other Support Services.  We kindly request that this amount may be settled using one of the following payment methods :  NEFT,  IMPS, UPI,  QR CODE,  PAYMENT LINK,   CHEQU,  DD, TT or SWIFT  TRANSFER, Payable to M/s  Paswi Manpower Consultants, Porur , Chennai  600116.  Our banking informations are detailed below:
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
                        <td colspan="3" style="text-align: right;"><b>Net Amount</b></td>
                        <td style="text-align: right;">{{ $formattedSubtotal ?? '' }}</td>
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
                    <div class="bank-title">Pay in Favour of M/s. PASWI MANPOWER CONSULTANTS, Bank : KARNATAKA BANK LTD,</div>
                    <div class="bank-info">Current A/C No. 1522-0001-0009-3801, IFSC CODE: KARB0000152</div>
                    <div class="bank-info">Swift Code of Karnataka Bank,  Porur Branch :  KARBINBBMDS, Chennai , INDIA.</div>
                </div>
                <div class="remarks-section">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{asset('/uploads/bankimages/bank 01.png')}}"/>
                        </div>
                        <div class="col-md-9">
                            <div>
                                <div class="bank-title">Bankers A/c. Details: Credit to A/c. No : 36252322 of Karnataka Bank Ltd.,</div>
                                <div class="bank-info">International Division , Mumbai , with citi bank NA , 111 Wall Street, New York, NY 10043.</div>
                                <div class="bank-info">Swift Code of Citi Bank , New York - CITIUS33 ,  Swift Code Of Karnataka Bank , Chennai  INDIA is KARBINBBMDS , Routing No : 0210089. Currency for Transfer in US$.</div>
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
                    <div>Muthiah V</div>
                    <div>Managing Partner</div>
                </div>
            </div>
        </div>
        
        <div class="no-print print-actions">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="mdi mdi-printer"></i> Print Invoice
            </button>
            <a href="{{ route('tax-international-invoice-reports.index') }}" class="btn btn-secondary">
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
