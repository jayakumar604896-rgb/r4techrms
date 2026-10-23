<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];
    
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}