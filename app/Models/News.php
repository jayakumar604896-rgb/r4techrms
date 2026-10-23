<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class News extends Model
{
    protected $fillable = [
    'title',
    'sub_title',
    'news_date',
    'image',
    'created_by',
    'is_active'
    ];
}