<?php

use Illuminate\Database\Migrations\Migration;

class CreateContestUserJoinTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contest_user', function($table){
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('contest_id');
            $table->timestamps();
        )};
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contest_user');
	}

}
