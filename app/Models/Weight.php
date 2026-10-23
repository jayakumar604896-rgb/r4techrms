<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_name',
        'excel_column',
        'weight',
        'is_active'
    ];

    // Get weight for a specific column
    public static function getWeight($column)
    {
        return self::where('excel_column', $column)
                  ->where('is_active', true)
                  ->value('weight') ?? 0;
    }

    // Get all active weights
    public static function getActiveWeights()
    {
        return self::where('is_active', true)->pluck('weight', 'excel_column');
    }
}