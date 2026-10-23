<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandReferenceSequence extends Model
{
    use HasFactory;
    
    protected $fillable = ['prefix', 'type', 'last_number'];
    
    public static function getNextNumber($prefix)
    {
        $sequence = self::where('prefix', $prefix)->first();
        
        if (!$sequence) {
            // Create new sequence if doesn't exist
            $sequence = self::create([
                'prefix' => $prefix,
                'type' => $prefix,
                'last_number' => 0
            ]);
        }
        
        $sequence->increment('last_number');
        return $sequence->last_number;
    }
}