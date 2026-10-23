<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TaxInvoiceReport extends Model
{
    use HasFactory;

    protected $table = 'tax_invoice';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'client_id',
        'invoice_no',
        'invoice_date',
        'lic_no',
        'tan',
        'pan_no',
        'particular',
        'nos',
        'unit_rate',
        'total_amount',
        'net_amount',
        'cgst',
        'sgst',
        'igst',
        'created_by',
    ];

    public $timestamps = true;
    
}