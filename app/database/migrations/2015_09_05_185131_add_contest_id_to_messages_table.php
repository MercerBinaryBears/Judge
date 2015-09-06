<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContestIdToMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('messages', function (Blueprint $table) {

        	// Foreign key for contests & messages
        	$table->integer('contest_id')
        	    ->unsigned()
        	    ->nullable();

        	$table->foreign('contest_id')
        		->references('id')
        		->on('contests')
        		->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('messages', function (Blueprint $table) {

        	$table->dropForeign('messages_contest_id_foreign');
        });
	}
}
