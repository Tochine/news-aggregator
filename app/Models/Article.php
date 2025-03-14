<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'source', 'source_code', 'author', 'description', 'content', 'url', 'category', 'image_url', 'published_at'
    ];
}
