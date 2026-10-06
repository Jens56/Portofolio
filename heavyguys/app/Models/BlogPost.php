<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $table = 'blog';
    protected $fillable = ['title', 'content', 'author_id'];
    
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'blog_post_id');
    }
}
