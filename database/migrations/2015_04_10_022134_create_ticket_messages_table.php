<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_message', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('ticket_id')->unsigned();
			$table->integer('user_id')->unsigned();
		
			$table->text('content');
			
			$table->softDeletes();
			$table->timestamps();
		});
		
		Schema::create('ticket_ticket_message', function(Blueprint $table) 
		{	
			$table->integer('ticket_id')->unsigned()->index();
			$table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
			
			$table->integer('ticket_message_id')->unsigned()->index();
			$table->foreign('ticket_message_id')->references('id')->on('ticket_message')->onDelete('cascade');
			
			$table->timestamps();
		});
		
		Schema::create('ticket_message_user', function(Blueprint $table) 
		{	
			$table->integer('ticket_message_id')->unsigned()->index();
			$table->foreign('ticket_message_id')->references('id')->on('ticket_message')->onDelete('cascade');
			
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
		Schema::drop('ticket_message');
		Schema::drop('ticket_ticket_message');
		Schema::drop('ticket_message_user');
	}

}
