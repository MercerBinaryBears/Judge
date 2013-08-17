<?php

use Illuminate\Database\Migrations\Migration;

class UpdateForeignKeyInProblemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('problems', function($table)
		{
			$table->dropForeign('posts_contest_id_foreign');
			$table->foreign('contest_id')->references('id')->on('contests');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('problems', function($table)
		{
			$table->dropForeign('posts_contest_id_foreign');
			$table->foreign('contest_id')->references('contests')->on('id');
		});
	}

}
