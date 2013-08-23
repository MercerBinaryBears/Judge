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
			$table->string('api_key');
			$table->timestamp('last_login')->nullable();
			$table->string('persist_code')->nullable();
			$table->boolean('activated')->default(1);
			$table->boolean('admin');
			$table->boolean('judge');
			$table->boolean('team');
			$table->timestamps();
		});

		// add the initial user, an admin
		Sentry::getUserProvider()->create(array(
			'username'=>'admin',
			'password'=>'admin',
			'api_key'=>User::generateApiKey(),
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