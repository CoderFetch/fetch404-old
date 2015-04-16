<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->integer('user_id')->unsigned();
			$table->text('content');

			$table->timestamps();
		});

		Schema::create('news_user', function(Blueprint $table)
		{
			$table->integer('news_id')->unsigned()->index();
			$table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');

			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('news');
	}

}
