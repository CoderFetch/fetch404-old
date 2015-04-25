<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use URL;

use Illuminate\Support\Facades\Auth;

class Category extends Model {

	//
	protected $table = 'categories';
	protected $fillable = ['name', 'description', 'weight', 'slug'];
	
	public function channels()
	{
		return $this->hasMany('App\Channel');
	}
	
	public function topics()
	{
		return $this->hasManyThrough('App\Topic', 'App\Channel');
	}

	public function categoryPermissions()
	{
		return $this->belongsToMany('App\CategoryPermission', 'category_permission', 'permission_id', 'permission_id');
	}

	public function categoryPermissionRoleIds()
	{
		return $this->categoryPermissions()->lists('role_id', 'role_id');
	}
	
	public function getPosts()
	{
		$posts = array();
		
		foreach($this->topics as $topic)
		{
			foreach($topic->posts as $post)
			{
				$posts[] = $post;
			}	
		}
		
		return $posts;
	}
	
	public function getRouteAttribute()
	{
		return route('forum.get.show.forum', ['slug' => $this->slug]);
	}

	public function canView($user = null)
	{
		$queryObj = CategoryPermission::select(array(
			'category_permission.permission_id',
			'category_permission.role_id',
			'category_permission.category_id'
		))->leftJoin('categories as c', function($join)
		{
			$join->on('category_permission.category_id', '=', 'c.id');
			//$join->on('category_forum_permission.role_id', '=', 1);
		})->with(
			'role',
			'category',
			'permission'
		)->where('category_id', '=', $this->id);

		$roleIds = $queryObj->lists('role_id', 'role_id');

		$success = false;

		$accessibleChannelCount = 0;

		foreach($this->channels as $channel)
		{
			if ($channel->canView(Auth::user()))
			{
				$accessibleChannelCount++;
			}
		}

		if ($accessibleChannelCount == 0)
		{
			// They can't see anything in the category anyway, so don't show it.
			$success = false;
		}

		if ($accessibleChannelCount > 0)
		{
			if ($user)
			{
				foreach($user->roles as $role)
				{
					if (in_array($role->id, $roleIds))
					{
						$success = true;
					}
				}
			}

			if (in_array(3, $roleIds))
			{
				return true;
			}

			if ($user)
			{
				return $user->roles->contains(3) || in_array(3, $roleIds) || in_array(1, $roleIds);
			}
		}

		return $success;
	}
}
