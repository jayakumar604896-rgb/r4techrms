<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_no', 'date', 'received_from', 'mobile_no', 'email',
        'amount_received', 'total_amount_due', 'balance_due', 'amount_words',
        'purpose', 'remittance_ref_no', 'mode_of_payment', 'paid_date',
        'is_refundable', 'is_non_refundable', 'is_security_deposit', 'received_by'
    ];

    protected $casts = [
        'date'          => 'date',
        'paid_date'     => 'date',
        'is_refundable' => 'boolean',
        'is_non_refundable' => 'boolean',
        'is_security_deposit' => 'boolean',
    ];
}