<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostEditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('post_edits', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->text('old_content');
			$table->text('new_content');
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
		Schema::drop('post_edits');
	}

}
