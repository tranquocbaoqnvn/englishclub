<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'tags';
    
    protected $searchableColumns = ['tags_name'];
    protected $fillable = ['tags_name'];

    public function news()
    {
        return $this->belongsToMany('App\Models\News', 'tags_news', 'tags_id', 'news_id');
    }

    public function posts()
    {
        return $this->belongsToMany('App\Models\News', 'tags_posts', 'tags_id', 'posts_id');
    }

    public function checkExist($tagsName)
    {
        $data = self::where('tags_name', $tagsName)->first();
        if($data){
            return true;
        }
        return false;
    }
}
