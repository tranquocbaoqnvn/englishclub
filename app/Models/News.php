<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tags', 'tags_news', 'news_id', 'tags_id');
    }
}
