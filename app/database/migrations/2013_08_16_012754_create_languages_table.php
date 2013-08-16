<?php

use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('languages', function($table){
			$table->increments('id');
			$table->string('name');
			$table->string('extension');
		});

		// Go ahead and create a couple of supported languages out of the box
		DB::table('languages')->insert(array(
			array('name'=>'C', 'extension'=>'c'),
			array('name'=>'C++', 'extension'=>'cpp'),
			array('name'=>'Java', 'extension'=>'java'),
			array('name'=>'Python', 'extension'=>'py'),
			));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('languages');
	}

}