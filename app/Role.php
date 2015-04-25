<?php namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	protected $fillable = ['name', 'display_name', 'description', 'is_superuser', 'is_default'];

	public function getCanBeDeletedAttribute()
	{
		return $this->is_protected == 0;
	}

	public function forumPermissions()
	{
		return $this->hasMany('App\ForumPermission');
	}
}