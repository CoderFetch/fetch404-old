<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('topic_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->text('content');
			
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('post_topic', function(Blueprint $table) // thread_post -> post_thread (conventions. ahh!)
		{	
			$table->integer('post_id')->unsigned()->index();
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
			
			$table->integer('topic_id')->unsigned()->index();
			$table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
			
			$table->timestamps();
		});
		
		Schema::create('post_user', function(Blueprint $table) // post_user -> post_user (conventions. ahh!)
		{	
			$table->integer('post_id')->unsigned()->index();
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
			
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
		Schema::drop('post_thread');
		Schema::drop('post_user');
	}

}
