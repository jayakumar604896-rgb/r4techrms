@extends('layouts.app')

@section('content')
<style>
    /* Print styles – hide everything except the receipt container */
    @media print {
        body * {
            visibility: hidden;
        }
        .receipt-container, .receipt-container * {
            visibility: visible;
        }
        .receipt-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            padding: 20px !important;
            margin: 0 !important;
            box-shadow: none;
            background: white;
        }
        .btn, .btn * {
            display: none !important;
        }
        @page {
            margin: 0.5in;
        }
    }

    /* On‑screen styles (original look) */
    .receipt-container {
        max-width: 800px;
        margin: 0 auto;
        border: 1px solid #000;
        padding: 20px;
        font-family: Arial, sans-serif;
        background: white;
    }
</style>

<div class="content-wrapper">
    <!-- The receipt content – wrapped in a container for print targeting -->
    <div class="receipt-container">
        <!-- Header -->
        <div style="text-align: center;">
            <h2>PASWI MANPOWER CONSULTANT</h2>
            <p>#20 A 9th Cross Street, Mangala Nagar, Porur, Chennai - 600116.</p>
            <p>Phone No: 044-4280 5883</p>
            <h3>CASH RECEIPT</h3>
        </div>

        <!-- Receipt No and Date -->
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <div><strong>Receipt No:</strong> {{ $receipt->receipt_no }}</div>
            <div><strong>Date:</strong> {{ $receipt->date->format('d/m/Y') }}</div>
        </div>

        <!-- Received from, Mobile, Email -->
        <div style="margin-top: 15px;">
            <div><strong>Received from:</strong> {{ $receipt->received_from }}</div>
            <div><strong>Mobile No.:</strong> {{ $receipt->mobile_no }}</div>
            <div><strong>Email:</strong> {{ $receipt->email }}</div>
        </div>

        <!-- Amount and Words -->
        <div style="margin-top: 15px;">
            <div><strong>Amount Rs.:</strong> {{ number_format($receipt->amount_received, 2) }}</div>
            <div><strong>Rupees in Words:</strong> {{ $receipt->amount_words }}</div>
        </div>

        <!-- Purpose -->
        <div style="margin-top: 15px;">
            <strong>Purpose:</strong> {{ $receipt->purpose }}
        </div>

        <!-- Main Table (exact replica) -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #000;">
            <!-- Row 1 -->
            <tr>
                <td style="border: 1px solid #000; padding: 8px;"><strong>Total Amount Due</strong></td>
                <td style="border: 1px solid #000; padding: 8px;">Rs. {{ number_format($receipt->total_amount_due, 2) }}</td>
                <td style="border: 1px solid #000; padding: 8px;"><strong>Remittance Ref No.</strong> {{ $receipt->remittance_ref_no }}</td>
                <td style="border: 1px solid #000; padding: 8px;"><strong>Mode of Payment</strong> {{ $receipt->mode_of_payment }}</td>
            </tr>
            <!-- Row 2 -->
            <tr>
                <td style="border: 1px solid #000; padding: 8px;"><strong>Amount Received</strong></td>
                <td style="border: 1px solid #000; padding: 8px;">Rs. {{ number_format($receipt->amount_received, 2) }}</td>
                <td style="border: 1px solid #000; padding: 8px;">Bank</td>
                <td style="border: 1px solid #000; padding: 8px;">Cheque</td>
            </tr>
            <!-- Row 3 -->
            <tr>
                <td style="border: 1px solid #000; padding: 8px;"><strong>Balance Due</strong></td>
                <td style="border: 1px solid #000; padding: 8px;">Rs. {{ number_format($receipt->balance_due, 2) }}</td>
                <td style="border: 1px solid #000; padding: 8px;"><strong>Paid Date</strong> {{ $receipt->paid_date ? $receipt->paid_date->format('d/m/Y') : '' }}</td>
                <td style="border: 1px solid #000; padding: 8px;">UPI/GPAY</td>
            </tr>
            <!-- Row 4 -->
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">Refundable 
                    <span style="border:1px solid #000; padding:0 10px;">{{ $receipt->is_refundable ? '✓' : '☐' }}</span>
                </td>
                <td style="border: 1px solid #000; padding: 8px;"></td>
                <td style="border: 1px solid #000; padding: 8px;">Non‑Refundable 
                    <span style="border:1px solid #000; padding:0 10px;">{{ $receipt->is_non_refundable ? '✓' : '☐' }}</span>
                </td>
                <td style="border: 1px solid #000; padding: 8px;">Security Deposit 
                    <span style="border:1px solid #000; padding:0 10px;">{{ $receipt->is_security_deposit ? '✓' : '☐' }}</span>
                </td>
            </tr>
            <!-- Row 5 -->
            <tr>
                <td style="border: 1px solid #000; padding: 8px;"></td>
                <td style="border: 1px solid #000; padding: 8px;"></td>
                <td style="border: 1px solid #000; padding: 8px;"></td>
                <td style="border: 1px solid #000; padding: 8px;">IMPS / QR</td>
            </tr>
        </table>

        <!-- Received by and Signature -->
        <div style="margin-top: 30px; display: flex; justify-content: space-between;">
            <div><strong>Received by:</strong> {{ $receipt->received_by }}</div>
            <div><strong>For PASWI MANPOWER CONSULTANT</strong></div>
        </div>
    </div>

    <!-- Buttons (outside receipt container – hidden when printing) -->
    <div style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
        <a href="{{ route('receipts.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection