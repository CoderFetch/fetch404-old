<?php

use Illuminate\Database\Seeder;

use App\Permission;
use App\Role;
use App\User;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
		$owner = new Role();
		
		$owner->name         = 'owner';
		$owner->display_name = 'Owner'; // optional
		$owner->description  = 'User is the owner of the website'; // optional
		
		$owner->save();
		//
		$member = new Role();
		
		$member->name         = 'member';
		$member->display_name = 'Member'; // optional
		$member->description  = 'User is a regular member of the website and has basic privileges'; // optional
		
		$member->save();
		
		// Assign the role(s)
		$user = User::where(
			'name',
			'=',
			'ItsLeo'
		)->first();
		
		if ($user)
		{
			$user->attachRole($owner);
		}
		
		// Permissions
		$adminPanel = new Permission();
		
		$adminPanel->name = 'admin-panel';
		$adminPanel->display_name = 'Admin CP Access'; // optional
		// Allow a user to...
		$adminPanel->description = 'Access the admin panel'; // optional
		
		$adminPanel->save();
		
		$owner->attachPermission($adminPanel);
    }

}