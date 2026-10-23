<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceProviderDetail extends Model
{
    use HasFactory;

    protected $table = 'service_provider_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'service_company_name',
        'type_business',
        'address',
        'company_location',
        'gst_number',
        'udhayam_number',
        'licence_number',
        'mcse_number',
        'agent_name',
        'desination',
        'mobile_number',
        'alternate_number',
        'email_id',
        'agent_name_two',
        'desination_two',
        'mobile_number_two',
        'alternate_number_two',
        'email_id_two',
        'status',
        'created_at',
        'updated_at',
		'user_type',
		'sub_division'
    ];

    public $timestamps = true;
    
}