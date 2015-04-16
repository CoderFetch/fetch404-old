<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->string('title');
			$table->string('slug');
			
			$table->integer('user_id')->unsigned();
			
			$table->boolean('locked')->default(0);
			
			$table->timestamps();
		});
		
		Schema::create('ticket_user', function(Blueprint $table)
		{
			$table->integer('ticket_id')->unsigned()->index();
			$table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
			
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			
			$table->timestamps();
		});
		
		Schema::create('ticket_participants', function(Blueprint $table)
		{
            $table->integer('ticket_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('user_id')->references('id')->on('users');
                       
            $table->timestamp('last_read');
            
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
		Schema::drop('tickets');
		Schema::drop('ticket_user');
		Schema::drop('ticket_participants');
	}

}
