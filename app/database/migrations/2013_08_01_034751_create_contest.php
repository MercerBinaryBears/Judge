<?php

use Illuminate\Database\Migrations\Migration;

class CreateContest extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contests', function($table){
			$table->increments('id');
			$table->string('name');
			$table->timestamp('starts_at');
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
		Schema::drop('contests');
	}

}