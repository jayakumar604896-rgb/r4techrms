<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'user_type',
        'sub_division',
        'industry_type',
        'company_location',
        'gst_number',
        'contact_person',
        'desination',
        'mobile_number',
        'alternate_number',
        'email_id',
        'created_by',
        'attachment_of_jd',
        'attachment_of_other_docs',
        'mea_number',
        'licence_number',
        'country',
        'state',
        'company_name',
        'address',
        'contact_person_two',
        'desination_two',
        'mobile_number_two',
        'alternate_number_two',
        'email_id_two',
        'status',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;
    
}