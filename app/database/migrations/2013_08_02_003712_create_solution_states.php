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

		// Create the Solution States data
		$now = Carbon\Carbon::now();
		$solution_states = array(	
			array(
				'name'=>'Correct',
				'is_correct'=>true,
			),
			array(
				'name'=>'Wrong Output',
				'is_correct'=>false,
			),
			array(
				'name'=>'Time Limit Exceeded',
				'is_correct'=>false,
			),
			array(
				'name'=>'Presentation Error',
				'is_correct'=>false,
			),
			array(
				'name'=>'Runtime Error',
				'is_correct'=>false,
			),
			array(
				'name'=>'Compile Error',
				'is_correct'=>false,
			),
			array(
				'name'=>'Judging',
				'is_correct'=>false,
				'pending'=>true,
			)
		);

		foreach($solution_states as $solution_state) {
			$solution_state['created_at'] = $now->format('Y-m-d H:i:s');
			$solution_state['updated_at'] = $now->format('Y-m-d H:i:s');
			DB::table('solution_states')->insert($solution_state);
		}

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
