<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropIsGlobalColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('is_global');
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
            $table->boolean('is_global')->default(false);
        });
	}

}
