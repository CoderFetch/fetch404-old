<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('channel_permission', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('permission_id')->index();
			$table->integer('role_id')->index();
			$table->integer('channel_id')->index();

			$table->timestamps();
		});

		Schema::create('channel_permission_role', function(Blueprint $table)
		{
			$table->integer('channel_permission_id')->unsigned()->index();
			$table->foreign('channel_permission_id')->references('id')->on('channel_permission')->onDelete('cascade');

			$table->integer('role_id')->unsigned()->index();
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

			$table->timestamps();
		});

		Schema::create('channel_permission_permission', function(Blueprint $table)
		{
			$table->integer('channel_permission_id')->unsigned()->index();
			$table->foreign('channel_permission_id')->references('id')->on('channel_permission')->onDelete('cascade');

			$table->integer('permission_id')->unsigned()->index();
			$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

			$table->timestamps();
		});

		Schema::create('channel_permission_channel', function(Blueprint $table)
		{
			$table->integer('channel_permission_id')->unsigned()->index();
			$table->foreign('channel_permission_id')->references('id')->on('channel_permission')->onDelete('cascade');

			$table->integer('channel_id')->unsigned()->index();
			$table->foreign('channel_id')->references('id')->on('categories')->onDelete('cascade');

			$table->timestamps();
		});
//
//		Schema::create('channel_role_role', function(Blueprint $table)
//		{
//			$table->integer('channel_role_id')->unsigned()->index();
//			$table->foreign('channel_role_id')->references('id')->on('channel_role')->onDelete('cascade');
//
//			$table->integer('role_id')->unsigned()->index();
//			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
//
//			$table->timestamps();
//		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('channel_permission');
		Schema::drop('channel_permission_role');
		Schema::drop('channel_permission_permission');
		Schema::drop('channel_permission_channel');
	}

}
