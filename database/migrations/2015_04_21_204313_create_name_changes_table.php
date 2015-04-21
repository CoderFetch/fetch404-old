<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNameChangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('name_changes', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('user_id')->unsigned();

			$table->string('old_name');
			$table->string('new_name');

			$table->timestamps();
		});

		Schema::create('name_change_user', function(Blueprint $table)
		{
			$table->integer('name_change_id')->unsigned()->index();
			$table->foreign('name_change_id')->references('id')->on('name_changes')->onDelete('cascade');

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
		Schema::drop('name_changes');
		Schema::drop('name_change_user');
	}

}
