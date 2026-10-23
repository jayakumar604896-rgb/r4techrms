<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brochure extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer'
    ];
}