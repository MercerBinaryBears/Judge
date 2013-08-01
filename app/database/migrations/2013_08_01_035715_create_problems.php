<?php

use Illuminate\Database\Migrations\Migration;

class CreateProblems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('problems', function($table){
			$table->increments('id');
			$table->string('name');
			$table->integer('contest_id');
			$table->text('judging_input');
			$table->text('judging_output');
			$table->timestamps();

			$table->foreign('contest_id')->references('contests')->on('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('problems');
	}

}