<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('category_permission', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('permission_id')->index();
			$table->integer('role_id')->index();
			$table->integer('category_id')->index();

			$table->timestamps();
		});

		Schema::create('category_permission_role', function(Blueprint $table)
		{
			$table->integer('category_permission_id')->unsigned()->index();
			$table->foreign('category_permission_id')->references('id')->on('category_permission')->onDelete('cascade');

			$table->integer('role_id')->unsigned()->index();
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

			$table->timestamps();
		});

		Schema::create('category_permission_permission', function(Blueprint $table)
		{
			$table->integer('category_permission_id')->unsigned()->index();
			$table->foreign('category_permission_id')->references('id')->on('category_permission')->onDelete('cascade');

			$table->integer('permission_id')->unsigned()->index();
			$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

			$table->timestamps();
		});

		Schema::create('category_permission_category', function(Blueprint $table)
		{
			$table->integer('category_permission_id')->unsigned()->index();
			$table->foreign('category_permission_id')->references('id')->on('category_permission')->onDelete('cascade');

			$table->integer('category_id')->unsigned()->index();
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

			$table->timestamps();
		});
//
//		Schema::create('category_role_role', function(Blueprint $table)
//		{
//			$table->integer('category_role_id')->unsigned()->index();
//			$table->foreign('category_role_id')->references('id')->on('category_role')->onDelete('cascade');
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
		Schema::drop('category_permission');
		Schema::drop('category_permission_role');
		Schema::drop('category_permission_permission');
		Schema::drop('category_permission_category');
	}

}
