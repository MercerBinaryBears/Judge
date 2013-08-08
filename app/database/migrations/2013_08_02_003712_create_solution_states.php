<?php

use Illuminate\Database\Migrations\Migration;

class CreateSolutionStates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('solution_states', function($table){
			$table->increments('id');
			$table->string('name');
			$table->boolean('is_correct');
			$table->boolean('pending')->default(0);
			$table->timestamps();
		});

		// add some
		DB::table('solution_states')->insert(array(
			'name'=>'Correct',
			'is_correct'=>true,
			));
		DB::table('solution_states')->insert(array(
			'name'=>'Wrong Output',
			'is_correct'=>false,
			));
		DB::table('solution_states')->insert(array(
			'name'=>'Time Limit Exceeded',
			'is_correct'=>false,
			));
		DB::table('solution_states')->insert(array(
			'name'=>'Presentation Error',
			'is_correct'=>false,
			));
		DB::table('solution_states')->insert(array(
			'name'=>'Runtime Error',
			'is_correct'=>false,
			));
		DB::table('solution_states')->insert(array(
			'name'=>'Compile Error',
			'is_correct'=>false,
			));
		DB::table('solution_states')->insert(array(
			'name'=>'Judging',
			'is_correct'=>false,
			'pending'=>true,
			));

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('solution_states');
	}

}