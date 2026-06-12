<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    // this is used to specify which fields can be mass assigned, it is used to prevent mass assignment vulnerabilities
    protected $fillable = ['title', 'description', 'category', 'paragraph','author', 'news_img', 'news_source', 'date', 'user_id'];

    protected $casts = [
        'date' => 'date',
        'paragraph' => 'array',
        'news_img' => 'array'
    ];
}