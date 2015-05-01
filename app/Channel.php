<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use URL;

class Channel extends Model {

	//
	protected $table = 'channels';
	protected $fillable = ['name', 'description', 'weight', 'category_id', 'slug'];
	
	public function category()
	{
		return $this->belongsTo('App\Category');
	}
	
	public function topics()
	{
		return $this->hasMany('App\Topic', 'channel_id')->with('user', 'channel', 'posts');
	}
	
	public function posts()
	{
		return $this->hasManyThrough('App\Post', 'App\Topic');
	}
		
	public function getRouteAttribute()
	{
		return URL::to('/forum/channel/' . $this->slug);
	}
	
	public function getCanCreateThreadAttribute()
	{
		return Auth::check();
	}

	public function channelPermissions()
	{
		return $this->belongsToMany('App\ChannelPermission', 'channel_permission', 'permission_id', 'permission_id');
	}

	public function can($permissionId, $user)
	{
		if ($user == null)
		{
			if ($permissionId == 17 || $permissionId == 20)
			{
				return true;
			}
		}

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
			return in_array(3, $queryObj->lists('role_id')) && ($permissionId == 17 || $permissionId == 20);
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
