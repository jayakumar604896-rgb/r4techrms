<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;

    protected $fillable = [
        // Basic Information
        'user_type',
        'demand_ref_no',
        'demand_open_date',
        'demand_closed_date',
        'recruiter_id',
        'recruiter_name',
        
        // Position Details
        'designation_offered',
        'client_agent_source',
        'no_of_positions',
        'qualification',
        'experience',
        'job_location',
        'sector_id',
        'industry',
        'candidate_location',
        'created_by',
        
        // Keywords Section
        'main_keyword_design',
        'main_keyword_qualification',
        'main_keyword_experience',
        'main_keyword_special_requirements',
        'secondary_keyword_notice',
        'secondary_keyword_salary_budget',
        'secondary_keyword_additional_3',
        'secondary_keyword_additional_4',
        'sector_industry',
        'a_sector_industry',
        'food',
        'accommodation_transport',
        'salary',
        'working_hours',
        'overtime',
        'a_candidate_location_requirement',
        'candidate_location_requirement',
        'service_charge',
        'certificate_special_requirements',
        
        // Interview & Selection Details
        'mode_of_interview',
        'mode_of_selection',
        'interview_date',
        'interview_location',
        'type_of_hiring',
        
        // Advertisement & Media
        'required_cvs',
        'advertisement_portals',
        'other_information',
        'status',
        'remarks',
        'sub_division',
        'other_industry'
    ];

    protected $casts = [
        'demand_open_date' => 'date',
        'demand_closed_date' => 'date',
        'interview_date' => 'date',
        'no_of_positions' => 'integer',
        'required_cvs' => 'integer',
        'recruiter_id' => 'integer',
        'sector_id' => 'integer',
        'mode_of_selection' => 'array',
        'type_of_hiring' => 'array',
        'advertisement_portals' => 'array',
    ];

    // Relationships
    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
    
    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors for JSON fields
    public function getModeOfSelectionAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function getTypeOfHiringAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function getAdvertisementPortalsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    // Mutators for JSON fields
    public function setModeOfSelectionAttribute($value)
    {
        $this->attributes['mode_of_selection'] = $value ? json_encode($value) : null;
    }

    public function setTypeOfHiringAttribute($value)
    {
        $this->attributes['type_of_hiring'] = $value ? json_encode($value) : null;
    }

    public function setAdvertisementPortalsAttribute($value)
    {
        $this->attributes['advertisement_portals'] = $value ? json_encode($value) : null;
    }

    // Scopes for filtering
    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'Closed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }
	public static function generateDemandRefNo($userType, $subDivision = null)
    {
        $number = null;
        $prefix = '';
        
        if ($userType == 'Domestic') {
            $prefix = 'DR';
            $number = DemandReferenceSequence::getNextNumber($prefix);
            return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
        } 
        elseif ($userType == 'International') {
            switch ($subDivision) {
                case 'GULF':
                    $prefix = 'GLF';
                    break;
                case 'SEA':
                    $prefix = 'SEA';
                    break;
                case 'EUROPE':
                    $prefix = 'ERU';
                    break;
                default:
                    $prefix = 'INT';
                    break;
            }
            
            $number = DemandReferenceSequence::getNextNumber($prefix);
            
            if ($subDivision == 'EUROPE' || $subDivision == 'SEA') {
                return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
            }
            
            return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
        }
        
        return null;
    }
}