<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::latest()->get();
        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        $lastReceipt = Receipt::orderBy('id', 'desc')->first();
        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_no, 3);
            $newNumber = $lastNumber + 1;
            $newReceiptNo = 'CR ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $newReceiptNo = 'CR 001';
        }

        return view('receipts.create', compact('newReceiptNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receipt_no'            => 'required|unique:receipts',
            'date'                  => 'required|date',
            'received_from'         => 'required|string|max:255',
            'mobile_no'             => 'required|string|max:20',
            'email'                 => 'nullable|email|max:255',
            'amount_received'       => 'required|numeric|min:0',
            'total_amount_due'      => 'required|numeric|min:0',
            'amount_words'          => 'required|string',
            'purpose'               => 'nullable|string',
            'remittance_ref_no'     => 'nullable|string|max:255',
            'mode_of_payment'       => 'nullable|string|max:50',
            'paid_date'             => 'nullable|date',
            'is_refundable'         => 'sometimes|boolean',
            'is_non_refundable'     => 'sometimes|boolean',
            'is_security_deposit'   => 'sometimes|boolean',
            'received_by'           => 'nullable|string|max:255',
        ]);

        // Calculate balance due
        $validated['balance_due'] = $validated['total_amount_due'] - $validated['amount_received'];

        // Handle checkboxes
        $validated['is_refundable'] = $request->has('is_refundable');
        $validated['is_non_refundable'] = $request->has('is_non_refundable');
        $validated['is_security_deposit'] = $request->has('is_security_deposit');

        Receipt::create($validated);

        return redirect()->route('receipts.index')->with('success', 'Receipt created successfully.');
    }

    public function show(Receipt $receipt)
    {
        return view('receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        return view('receipts.edit', compact('receipt'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'receipt_no'            => 'required|unique:receipts,receipt_no,' . $receipt->id,
            'date'                  => 'required|date',
            'received_from'         => 'required|string|max:255',
            'mobile_no'             => 'required|string|max:20',
            'email'                 => 'nullable|email|max:255',
            'amount_received'       => 'required|numeric|min:0',
            'total_amount_due'      => 'required|numeric|min:0',
            'amount_words'          => 'required|string',
            'purpose'               => 'nullable|string',
            'remittance_ref_no'     => 'nullable|string|max:255',
            'mode_of_payment'       => 'nullable|string|max:50',
            'paid_date'             => 'nullable|date',
            'is_refundable'         => 'sometimes|boolean',
            'is_non_refundable'     => 'sometimes|boolean',
            'is_security_deposit'   => 'sometimes|boolean',
            'received_by'           => 'nullable|string|max:255',
        ]);

        $validated['balance_due'] = $validated['total_amount_due'] - $validated['amount_received'];
        $validated['is_refundable'] = $request->has('is_refundable');
        $validated['is_non_refundable'] = $request->has('is_non_refundable');
        $validated['is_security_deposit'] = $request->has('is_security_deposit');

        $receipt->update($validated);

        return redirect()->route('receipts.index')->with('success', 'Receipt updated successfully.');
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt deleted successfully.');
    }
}