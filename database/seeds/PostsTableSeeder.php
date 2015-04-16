<?php

use Illuminate\Database\Seeder;

use App\Post;

class PostsTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $post = Post::create(
        	[
        		'thread_id' => 1,
        		'user_id' => 1,
        		'content' => 'This is an announcement. Some details here. A conclusion.',
        	]
        );
        $reply = Post::create(
        	[
        		'thread_id' => 1,
        		'user_id' => 1,
        		'content' => 'thanks for telling us',
        	]
        );
    }

}