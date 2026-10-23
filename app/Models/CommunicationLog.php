<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class CommunicationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'to_email',
        'subject',
        'content',
        'candidate_id',
        'attachments',
        'cc_email',
        'user_type',
        'sub_division',
        'created_by'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];
}
