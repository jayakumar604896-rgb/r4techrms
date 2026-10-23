<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'agent_company_name',
        'type_business',
        'address',
        'company_location',
        'agent_name',
        'gst_number',
        'desination',
        'mobile_number',
        'alternate_number',
        'sub_division',
        'email_id',
        'agent_business_profile_attachment',
        'status',
        'mea_number',
        'licence_number',
        'agent_name_two',
        'desination_two',
        'mobile_number_two',
        'alternate_number_two',
        'email_id_two',
        'type',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;
    
}