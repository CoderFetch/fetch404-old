<?php

use Illuminate\Database\Seeder;

use App\Category;

class CategoriesTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $announcements = Category::create(
        	[
        		'name' => 'Announcements',
        		'description' => null,
        		'weight' => 1
        	]
        );
        
        $serverChat = Category::create(
        	[
        		'name' => 'Server Chat',
        		'description' => null,
        		'weight' => 2
        	]
        );
    }

}