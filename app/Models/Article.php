<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'slug', 'category', 'excerpt', 'content', 'image_url', 'read_time', 'author', 'is_published', 'created_by'];

    protected $casts = ['is_published' => 'boolean'];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function scopePublished($query) { return $query->where('is_published', true); }
}
