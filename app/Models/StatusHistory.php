<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class StatusHistory extends Model
{
    protected $fillable = [
        'candidate_id',
        'status',
        'company_id',
        'interview_date',
        'remarks',
        'updated_by'
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function company()
    {
        return $this->belongsTo(Client::class, 'company_id');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}