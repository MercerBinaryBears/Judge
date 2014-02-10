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
			$table->string('username', 32);
			$table->string('password', 64);
			$table->string('api_key');
			$table->boolean('admin')->default(false);
			$table->boolean('judge')->default(false);
			$table->boolean('team')->default(false);
			$table->timestamps();
		});

		// add the initial user, an admin
		$u = new User();
		$u->username = 'admin';
		$u->password = 'admin';
		$u->admin = true;
		$u->save();
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
