<?php namespace Fetch404\Core\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;
use URL;

use Illuminate\Support\Facades\Auth;

class Category extends Model {

	//
	protected $table = 'categories';
	protected $fillable = ['name', 'description', 'weight', 'slug'];
	
	public function channels()
	{
		return $this->hasMany('Fetch404\Core\Models\Channel');
	}
	
	public function topics()
	{
		return $this->hasManyThrough('Fetch404\Core\Models\Topic', 'Fetch404\Core\Models\Channel');
	}

	public function categoryPermissions()
	{
		return $this->belongsToMany('Fetch404\Core\Models\CategoryPermission', 'category_permission', 'permission_id', 'permission_id');
	}

	public function categoryPermissionRoleIds()
	{
		return $this->categoryPermissions()->lists('role_id', 'role_id');
	}

	public function getTopics()
	{
		$topicsArray = array();

		foreach($this->topics as $topic)
		{
			$topicsArray[] = $topic;
		}

		$topics = Collection::make($topicsArray);

		$topics = $topics->filter(function($item)
		{
			return $item->channel->category->canView(Auth::user()) && $item->channel->canView(Auth::user());
		});

		return $topics;
	}

	public function getPosts()
	{
		$postsArray = array();

		foreach($this->topics as $topic)
		{
			foreach($topic->posts as $post)
			{
				$postsArray[] = $post;
			}
		}

		$posts = Collection::make($postsArray);

		$posts = $posts->filter(function($item)
		{
			return $item->topic->channel->category->canView(Auth::user()) && $item->topic->channel->canView(Auth::user());
		});

		return $posts;
	}
	
	public function getRouteAttribute()
	{
		return route('forum.get.show.forum', [$this->id]);
	}

	public function can($permissionId, $user)
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

		$permissions = $queryObj->get();

		if ($user == null)
		{
			if (in_array(2, $queryObj->lists('role_id')))
			{
				if ($permissionId == 17 || $permissionId == 20)
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

					if (!$user)
					{
						return ($permission->role->id == 3 || $permission->role->id == 2);
					}
				}
			}
		}

		return false;
	}

	public function canView($user = null)
	{
		return $this->can(20, $user);
	}
}
