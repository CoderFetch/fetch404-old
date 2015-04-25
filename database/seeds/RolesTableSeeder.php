<?php

use Illuminate\Database\Seeder;

use App\Permission;
use App\Role;
use App\User;

class RolesTableSeeder extends Seeder {

    public function run()
    {
		//Role::truncate();
        // TestDummy::times(20)->create('App\Post');
		$groups = ['Administrator', 'Guest', 'Member', 'Moderator'];

		foreach($groups as $group)
		{
			$role = new Role();

			$role->name = $group;

			if ($group == 'Administrator')
			{
				$role->is_superuser = 1;
			}

			if ($group == 'Member')
			{
				$role->is_default = 1;
			}

			$role->save();
		}
    }

}