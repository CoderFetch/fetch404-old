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

	public function can($permissionId, $user)
	{
//		if ($user == null)
//		{
//			if ($permissionId == 17 || $permissionId == 20)
//			{
//				return true;
//			}
//		}

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

					if (!$user)
					{
						return $permission->role->id == 3;
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
