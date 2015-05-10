<?php namespace Fetch404\Core\Models;

use Carbon\Carbon;
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

    public function formattedCreatedAt()
    {
        $now = Carbon::now();
        $now->subDays(7);

        return ($this->created_at > $now->toDateTimeString() ? $this->created_at->diffForHumans() : $this->created_at->format('M jS, Y'));
    }
}
