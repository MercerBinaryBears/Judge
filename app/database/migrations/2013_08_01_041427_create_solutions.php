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
			$table->integer('language_id');
			$table->text('solution_filename');
			$table->text('solution_state_id');
			$table->integer('claiming_judge_id')->nullable();
			$table->timestamps();

			$table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages');
			$table->foreign('solution_state_id')->references('id')->on('solution_states');
			$table->foreign('claiming_judge_id')->references('id')->on('users');
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
