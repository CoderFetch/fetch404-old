<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;
use URL;

class Channel extends Model {

	//
	protected $table = 'channels';
	protected $fillable = ['name', 'description', 'weight', 'category_id', 'slug'];
	
	public function category()
	{
		return $this->belongsTo('Fetch404\Core\Models\Category');
	}
	
	public function topics()
	{
		return $this->hasMany('Fetch404\Core\Models\Topic', 'channel_id')
			->with('user', 'channel', 'posts')
			->orderBy('pinned', 'desc');
	}
	
	public function posts()
	{
		return $this->hasManyThrough('Fetch404\Core\Models\Post', 'Fetch404\Core\Models\Topic');
	}

	public function getLatestPost()
	{
		return $this->posts()->latest('created_at')->first();
	}

	public function hasUnreadTopics()
	{
		foreach($this->topics as $topic)
		{
			if ($topic->userReadStatus == 'unread' || $topic->userReadStatus == 'updated')
			{
				return true;
			}
		}

		return false;
	}
		
	public function getRouteAttribute()
	{
		return route('forum.get.show.channel', [$this->id]);
	}

	public function getTopicsPaginatedAttribute()
	{
		return $this->topics()->paginate(15);
	}

	public function getLastPageAttribute()
	{
		return $this->topicsPaginated->lastPage();
	}

	public function getPageLinksAttribute()
	{
		return $this->topicsPaginated->render();
	}

	public function getHasPagesAttribute()
	{
		return $this->topicsPaginated->hasPages();
	}
	
	public function getCanCreateThreadAttribute()
	{
		return Auth::check();
	}

	public function channelPermissions()
	{
		return $this->belongsToMany('Fetch404\Core\Models\ChannelPermission', 'channel_permission', 'permission_id', 'permission_id');
	}

	public function can($permissionId, $user)
	{
		$queryObj = ChannelPermission::select(array(
			'channel_permission.permission_id',
			'channel_permission.role_id',
			'channel_permission.channel_id'
		))->leftJoin('channels as ch', function($join)
		{
			$join->on('channel_permission.channel_id', '=', 'ch.id');
			//$join->on('category_forum_permission.role_id', '=', 1);
		})->with(
			'role',
			'channel',
			'permission'
		)->where('channel_id', '=', $this->id);

		$permissions = $queryObj->get();

		if ($user == null)
		{
			if (in_array(2, $queryObj->lists('role_id')))
			{
				if ($permissionId == 17 || $permissionId == 21)
				{
					return true;
				}
			}
			return false;
		}

		if ($user && $user->roles->contains(1))
		{
			return true;
		}

		foreach($permissions as $permission)
		{
			$permissionById = Permission::find($permissionId);

			if ($permissionById)
			{
				if ($permission->permission->id == $permissionId)
				{
					if ($user && $user->roles->contains($permission->role->id))
					{
						return true;
					}
				}
			}
		}

		return false;
	}

	public function canView($user = null)
	{
		return $this->can(21, $user);
	}
}
