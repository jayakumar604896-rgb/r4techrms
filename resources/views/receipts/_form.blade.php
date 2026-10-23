@csrf

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="receipt_no" class="form-label">Receipt No. <span class="required-star">*</span></label>
        <input type="text" name="receipt_no" id="receipt_no" class="form-control" value="{{ old('receipt_no', $receipt->receipt_no ?? $newReceiptNo ?? '') }}" readonly>
    </div>
    <div class="col-md-3 mb-3">
        <label for="date" class="form-label">Date <span class="required-star">*</span></label>
        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $receipt->date ?? date('Y-m-d')) }}" required>
    </div>

    <div class="col-md-3 mb-3">
        <label for="received_from" class="form-label">Received from <span class="required-star">*</span></label>
        <input type="text" name="received_from" id="received_from" class="form-control" value="{{ old('received_from', $receipt->received_from ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="mobile_no" class="form-label">Mobile No. <span class="required-star">*</span></label>
        <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="{{ old('mobile_no', $receipt->mobile_no ?? '') }}" required>
        <small class="text-muted">Max 15 digits</small>
    </div>
    <div class="col-md-3 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $receipt->email ?? '') }}">
    </div>

    <div class="col-md-3 mb-3">
        <label for="amount_received" class="form-label">Amount Rs. <span class="required-star">*</span></label>
        <input type="number" step="0.01" name="amount_received" id="amount_received" class="form-control" value="{{ old('amount_received', $receipt->amount_received ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="total_amount_due" class="form-label">Total Amount Due <span class="required-star">*</span></label>
        <input type="number" step="0.01" name="total_amount_due" id="total_amount_due" class="form-control" value="{{ old('total_amount_due', $receipt->total_amount_due ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="balance_due" class="form-label">Balance Due</label>
        <input type="number" step="0.01" name="balance_due" id="balance_due" class="form-control" value="{{ old('balance_due', $receipt->balance_due ?? '') }}" readonly>
    </div>
</div>

<div class="mb-3">
    <label for="amount_words" class="form-label">Rupees in Words <span class="required-star">*</span></label>
    <textarea name="amount_words" id="amount_words" rows="2" class="form-control" required>{{ old('amount_words', $receipt->amount_words ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="purpose" class="form-label">Purpose</label>
    <textarea name="purpose" id="purpose" rows="2" class="form-control">{{ old('purpose', $receipt->purpose ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="remittance_ref_no" class="form-label">Remittance Ref No.</label>
        <input type="text" name="remittance_ref_no" id="remittance_ref_no" class="form-control" value="{{ old('remittance_ref_no', $receipt->remittance_ref_no ?? '') }}">
    </div>
    <div class="col-md-3 mb-3">
        <label for="mode_of_payment" class="form-label">Mode of Payment</label>
        <select name="mode_of_payment" id="mode_of_payment" class="form-select">
            <option value="">-- Select --</option>
            <option value="Bank" {{ old('mode_of_payment', $receipt->mode_of_payment ?? '') == 'Bank' ? 'selected' : '' }}>Bank</option>
            <option value="Cheque" {{ old('mode_of_payment', $receipt->mode_of_payment ?? '') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
            <option value="DD" {{ old('mode_of_payment', $receipt->mode_of_payment ?? '') == 'DD' ? 'selected' : '' }}>DD</option>
            <option value="UPI/GPAY" {{ old('mode_of_payment', $receipt->mode_of_payment ?? '') == 'UPI/GPAY' ? 'selected' : '' }}>UPI/GPAY</option>
            <option value="NEFT" {{ old('mode_of_payment', $receipt->mode_of_payment ?? '') == 'NEFT' ? 'selected' : '' }}>NEFT</option>
            <option value="IMPS" {{ old('mode_of_payment', $receipt->mode_of_payment ?? '') == 'IMPS' ? 'selected' : '' }}>IMPS</option>
            <option value="QR" {{ old('mode_of_payment', $receipt->mode_of_payment ?? '') == 'QR' ? 'selected' : '' }}>QR</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label for="paid_date" class="form-label">Paid Date</label>
        <input type="date" name="paid_date" id="paid_date" class="form-control" value="{{ old('paid_date', $receipt->paid_date ?? '') }}">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="form-check">
            <input type="checkbox" name="is_refundable" id="is_refundable" class="form-check-input" value="1" {{ old('is_refundable', $receipt->is_refundable ?? false) ? 'checked' : '' }}>
            <label for="is_refundable" class="form-check-label">Refundable</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check">
            <input type="checkbox" name="is_non_refundable" id="is_non_refundable" class="form-check-input" value="1" {{ old('is_non_refundable', $receipt->is_non_refundable ?? false) ? 'checked' : '' }}>
            <label for="is_non_refundable" class="form-check-label">Non‑Refundable</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-check">
            <input type="checkbox" name="is_security_deposit" id="is_security_deposit" class="form-check-input" value="1" {{ old('is_security_deposit', $receipt->is_security_deposit ?? false) ? 'checked' : '' }}>
            <label for="is_security_deposit" class="form-check-label">Security Deposit</label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="received_by" class="form-label">Received by</label>
    <input type="text" name="received_by" id="received_by" class="form-control" value="{{ old('received_by', $receipt->received_by ?? '') }}">
</div>

<div class="row mt-4">
    <div class="col-12">
        <button type="submit" class="btn btn-primary me-2">
            <i class="mdi mdi-content-save me-2"></i>Save Receipt
        </button>
        <a href="{{ route('receipts.index') }}" class="btn btn-light">
            <i class="mdi mdi-arrow-left me-2"></i>Cancel
        </a>
    </div>
</div>