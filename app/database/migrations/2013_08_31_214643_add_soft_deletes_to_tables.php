<?php

use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table) {
			$table->softDeletes();
		});

		Schema::table('contests', function($table) {
        		$table->softDeletes();
		});

		Schema::table('problems', function($table) {
        		$table->softDeletes();
        	});

		Schema::table('solutions', function($table) {
            		$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table) {
			$table->dropColumn('deleted_at');
		});

		Schema::table('contests', function($table) {
        		$table->dropColumn('deleted_at');
		});

		Schema::table('problems', function($table) {
        		$table->dropColumn('deleted_at');
        	});

		Schema::table('solutions', function($table) {
            		$table->dropColumn('deleted_at');
        	});
	}

}
