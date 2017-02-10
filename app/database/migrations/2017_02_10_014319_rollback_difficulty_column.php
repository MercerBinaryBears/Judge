<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RollbackDifficultyColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('problems', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('problems', function (Blueprint $table) {
            $table->integer('difficulty')->default(1);
        });
	}

}
