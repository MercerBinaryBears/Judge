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

            // Set foreign key relationship between users and contests
            $table->foreign('user_id')->references('id')->on('contests')->on_delete('cascade')->on_update('cascade');
            $table->foreign('contest_id')->references('id')->on('users')->on_delete('cascade')->on_update('cascade');
        });
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
