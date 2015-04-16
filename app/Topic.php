<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use URL;

class Topic extends Model {

	//
	protected $table = 'topics';
	protected $fillable = ['title', 'user_id', 'channel_id', 'locked', 'pinned', 'slug'];
	
	public function channel()
	{
		return $this->belongsTo('App\Channel');
	}
	
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function posts()
	{
		return $this->hasMany('App\Post');
	}
	
	public function getPostsPaginatedAttribute()
	{
		return $this->posts()->paginate(config('forumsettings.threads.postsPerPage', 5));
	}
	
	public function getLastPageAttribute()
	{
		return $this->postsPaginated->lastPage();
	}	
	
	public function getPageLinksAttribute()
	{
		return $this->postsPaginated->render();
	}
	
	public function getRouteAttribute()
	{
		return route('forum.get.show.thread', ['slug' => $this->slug, 'id' => $this->id]);
	}
	
	public function getShowReplyRouteAttribute()
	{
		return route('forum.get.show.thread.reply', ['slug' => $this->slug, 'id' => $this->id]);
	}

	public function getReplyRouteAttribute()
	{
		return route('forum.post.thread.reply', ['slug' => $this->slug, 'id' => $this->id]);
	}
	
	public function getQuickReplyRouteAttribute()
	{
		return route('forum.post.quick-reply.thread', ['slug' => $this->slug, 'id' => $this->id]);
	}
	
	public function getReplyCountAttribute()
	{
		$replyCount = $this->posts()->count() - 1;
		
		return $replyCount < 0 ? 0 : $replyCount;
	}
	
	public function getCanReplyAttribute()
	{
		return Auth::check() && Auth::user()->isConfirmed() && $this->locked == 0;
	}
}
