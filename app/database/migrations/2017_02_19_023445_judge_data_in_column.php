<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Judge\Models\Problem;

class JudgeDataInColumn extends Migration {

	/**
	 * This should essentially make the judging input and output columns longer so they can store more
	 *
	 * @return void
	 */
	public function up()
	{
        // Copy the old filenames into a temp column
        Schema::table('problems', function (Blueprint $table) {
            $table->text('judging_input_filename')->default('');
            $table->text('judging_output_filename')->default('');
        });
        DB::statement('update problems set judging_input=judging_input_filename, judging_output=judging_output_filename');

        // Recreate the judging input and output columns, but as long text
        Schema::table('problems', function (Blueprint $table) {
            $table->dropColumn('judging_input', 'judging_output');
        });
        Schema::table('problems', function (Blueprint $table) {
            $table->longText('judging_input')->default('');
            $table->longText('judging_output')->default('');
        });

        // Copy the data back over and drop the old temp columns
        DB::statement('update problems set judging_input=judging_input_filename, judging_output=judging_output_filename');
        Schema::table('problems', function (Blueprint $table) {
            $table->dropColumn('judging_input_filename', 'judging_output_filename');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        // this migration is okay if it isn't undone. It's idempotent :)
	}
}
