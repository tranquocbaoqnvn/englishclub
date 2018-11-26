<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tags', 'tags_posts', 'posts_id', 'tags_id');
    }
}
