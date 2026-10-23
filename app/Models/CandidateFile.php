<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'file_title',
        'file_path',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}