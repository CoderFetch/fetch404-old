<?php

use Illuminate\Database\Seeder;

use App\Channel;

class ChannelsTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $news = Channel::create(
        	[
        		'title' => 'News',
        		'description' => null,
        		'weight' => 1,
        		'category_id' => 1,
        		'slug' => 'news'
        	]
        );
        
        $serverDiscussion = Channel::create(
        	[
        		'title' => 'Server Discussion',
        		'description' => null,
        		'weight' => 1,
        		'category_id' => 2,
        		'slug' => 'server-discussion'
        	]
        );
    }

}