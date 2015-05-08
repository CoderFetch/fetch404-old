<?php namespace Fetch404\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Auth;
use URL;

use Zizaco\Entrust\EntrustFacade as Entrust;

class Topic extends Model {

	//
	protected $table = 'topics';
	protected $fillable = ['title', 'user_id', 'channel_id', 'locked', 'pinned', 'slug'];

	// Thread constants
	const     STATUS_UNREAD  = 'unread';
	const     STATUS_UPDATED = 'updated';

	public function channel()
	{
		return $this->belongsTo('Fetch404\Core\Models\Channel');
	}
	
	public function user()
	{
		return $this->belongsTo('Fetch404\Core\Models\User', 'user_id');
	}
	
	public function posts()
	{
		return $this->hasMany('Fetch404\Core\Models\Post');
	}

	public function isLocked()
	{
		return $this->locked == 1;
	}

	public function isPinned()
	{
		return $this->pinned == 1;
	}

	public function getLatestPost()
	{
		$posts = $this->posts()->orderBy('created_at', 'desc');

		return $posts->first();
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
		return route('forum.get.show.thread', [$this->channel->id, $this->id]);
	}
	
	public function getShowReplyRouteAttribute()
	{
		return route('forum.get.show.thread.reply', [$this->channel->id, $this->id]);
	}

	public function getReplyRouteAttribute()
	{
		return route('forum.post.thread.reply', [$this->channel->id, $this->id]);
	}
	
	public function getQuickReplyRouteAttribute()
	{
		return route('forum.post.quick-reply.thread', [$this->channel->id, $this->id]);
	}

	public function getCanViewAttribute()
	{
		return ($this->channel->canView(Auth::user()) && $this->channel->category->canView(Auth::user()));
	}
	
	public function getReplyCountAttribute()
	{
		$replyCount = $this->posts()->count() - 1;
		
		return $replyCount < 0 ? 0 : $replyCount;
	}
	
	public function getCanReplyAttribute()
	{
		if (Entrust::can('replyToAllThreads')) return true;

		if (Auth::check() && Auth::user()->isConfirmed() && $this->locked == 0 && $this->channel->can(6, Auth::user())) return true;

		return false;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getOldAttribute()
	{
		$now = Carbon::now();
		$now->subMonths(1);

		// The thread is old if there have been no posts since a month ago.

		return $this->updated_at->toDateTimeString() < $now->toDateTimeString();
	}

	public function readers()
	{
		return $this->belongsToMany('Fetch404\Core\Models\User', 'forum_threads_read', 'topic_id', 'user_id')->withTimestamps();
	}

	// Current user: reader attributes
	public function getReaderAttribute()
	{
		if (!is_null(Auth::user()))
		{
			$reader = $this->readers()->where('user_id', '=', Auth::id())->first();
			return (!is_null($reader)) ? $reader->pivot : null;
		}

		return null;
	}

	public function getUserReadStatusAttribute()
	{
		if (!$this->old && !is_null(Auth::user()))
		{
			if (is_null($this->reader))
			{
				return self::STATUS_UNREAD;
			}

			return ($this->updatedSince($this->reader)) ? self::STATUS_UPDATED : false;
		}

		return false;
	}

	public function updatedSince(&$model)
	{
		return ($this->updated_at > $model->updated_at);
	}

	public function markAsRead($userID)
	{
		if (is_null($this->reader))
		{
			$this->readers()->attach($userID);
		}
		elseif ($this->updatedSince($this->reader))
		{
			$this->reader->touch();
		}
	}
}
