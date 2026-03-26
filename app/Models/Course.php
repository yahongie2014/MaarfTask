<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'category_id', 'title', 'author', 'thumbnail', 'youtube_id', 'description', 'duration', 'views'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
