<?php

use Illuminate\Database\Seeder;

use App\Thread;

class ThreadsTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $thread1 = Thread::create(
        	[
        		'title' => 'Just an announcement',
        		'slug' => 'just-an-announcement',
        		'user_id' => 1,
        		'channel_id' => 1,
        		'locked' => 0,
        		'pinned' => 1
        	]
        );
    }

}