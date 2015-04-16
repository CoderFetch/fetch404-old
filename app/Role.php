<?php namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
	public function getCanBeDeletedAttribute()
	{
		return $this->is_protected == 0;
	}
}