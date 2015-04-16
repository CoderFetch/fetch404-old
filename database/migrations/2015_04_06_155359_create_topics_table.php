<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topics', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->string('title');
			$table->string('slug');
			
			$table->integer('user_id')->unsigned();
			$table->integer('channel_id')->unsigned();
			
			$table->boolean('locked')->default(0);
			$table->boolean('pinned')->default(0);
			
			$table->timestamps();
		});

		Schema::create('channel_topic', function(Blueprint $table) 
		{	
			$table->integer('channel_id')->unsigned()->index();
			$table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
			
			$table->integer('topic_id')->unsigned()->index();
			$table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
			
			$table->timestamps();
		});
		
		Schema::create('topic_user', function(Blueprint $table)  //thread_user
		{	
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
			$table->integer('topic_id')->unsigned()->index();
			$table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
			
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
		Schema::drop('topics');
		Schema::drop('channel_topic');
		Schema::drop('topic_user');
	}

}
