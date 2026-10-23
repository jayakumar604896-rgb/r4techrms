<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];
    
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
    
    // Scope for active sectors
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}