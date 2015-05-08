<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model {

	//
    protected $table = 'news';

    protected $fillable = ['title', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo('Fetch404\Core\Models\User');
    }

    public function tags()
    {
        return $this->belongsToMany('Fetch404\Core\Models\Tag');
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
