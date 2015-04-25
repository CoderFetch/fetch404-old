<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('subject_id')->nullable()->index();
			$table->string('subject_type')->nullable()->index();
			$table->string('name');
			$table->integer('user_id')->index();
			$table->integer('sender_id')->nullable()->index();

			$table->boolean('is_read')->default(false);

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
		Schema::drop('notifications');
	}

}
