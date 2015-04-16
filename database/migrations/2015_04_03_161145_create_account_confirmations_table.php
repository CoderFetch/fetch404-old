<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountConfirmationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_confirmations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->index()->references('id')->on('users')->onDelete('cascade');
			$table->integer('expires_at')->default((time() + 3600));
			$table->string('code');
			
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
		Schema::drop('account_confirmations');
	}

}
