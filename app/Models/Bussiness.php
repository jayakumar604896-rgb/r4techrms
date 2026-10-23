<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bussiness extends Model
{
    protected $table = 'bussiness';

    protected $fillable = ['name', 'is_active'];
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}