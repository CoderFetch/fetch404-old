<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('channels', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->string('name');
			$table->string('slug');
			$table->text('description')->nullable();
			$table->integer('weight');
			$table->integer('category_id');
			
			$table->timestamps();
		});
		
		Schema::create('category_channel', function(Blueprint $table)
		{
			$table->integer('category_id')->unsigned()->index();
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			
			$table->integer('channel_id')->unsigned()->index();
			$table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
			
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
		Schema::drop('channels');
		Schema::drop('category_channel');
	}

}
