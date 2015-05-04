<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('subject_id')->index();
			$table->string('subject_type')->index();

			$table->string('reason', 255);

			$table->integer('user_id');
			$table->integer('reported_id');

			$table->boolean('closed')->default(false);

			$table->timestamps();
		});

		Schema::create('report_comments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('report_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->text('body');
			$table->timestamps();

			$table->foreign('report_id')->references('id')->on('reports');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('report_comments');
		Schema::drop('reports');
	}

}
