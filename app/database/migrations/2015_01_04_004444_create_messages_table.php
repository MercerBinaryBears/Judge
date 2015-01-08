<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');

            // The problem the message pertains to
            $table->integer('problem_id')->unsigned()->nullable();

            // The message body
            $table->text('text');

            // The sender of the message
            $table->integer('sender_id')->unsigned();

            // The response text, if any
            $table->text('response_text')->nullable();

            // The responder (a judge)
            $table->text('responder_id')->unsigned()->nullable();

            // helper flag for determining if the message is global to all teams. This is only true for messages that judges send.
            $table->boolean('is_global')->default(false);

            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('messages');
	}

}
