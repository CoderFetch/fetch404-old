<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model {

	//
    protected $table = 'news';

    protected $fillable = ['title', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function tagNames()
    {
        $names = [];
        foreach($this->tags as $tag)
        {
            $names[] = $tag->name;
        }

        return implode(", ", $names);
    }
}
