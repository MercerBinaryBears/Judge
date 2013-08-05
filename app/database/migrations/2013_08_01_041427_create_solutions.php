<?php

use Illuminate\Database\Migrations\Migration;

class CreateSolutions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('solutions', function($table){
			$table->increments('id');
			$table->integer('problem_id');
			$table->integer('user_id');
			$table->text('solution_code');
			$table->text('solution_language');
			$table->text('solution_filename');
			$table->text('solution_state_id');
			$table->integer('claiming_judge_id')->nullable();
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
		Schema::drop('solutions');
	}

}