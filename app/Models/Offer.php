<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_type',
    'employer_name',
    'job_location',
    'industry',
    'designation_offered',
    'employment_type',
    'salary_offered',
    'allowance_offered',
    'total_salary',
    'food',
    'accommodation',
    'air_ticket',
    'medical_test',
    'certificate_attestation',
    'pcc',
    'passport_status',
    'passport_validity',
    'willing_to_work',
    'notice_period',
    'other_information',
    'signature',
    'sub_division'
];

}
