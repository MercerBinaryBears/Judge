<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table){
			$table->increments('id');
			$table->string('username');
			$table->string('password');
			$table->boolean('admin');
			$table->boolean('judge');
			$table->boolean('team');
			$table->timestamps();
		});

		// add the initial user, an admin
		Sentry::getUserProvider()->create(array(
			'username'=>'admin',
			'password'=>'admin',
			'admin'=>true,
			'judge'=>false,
			'team'=>false,
			));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}

}