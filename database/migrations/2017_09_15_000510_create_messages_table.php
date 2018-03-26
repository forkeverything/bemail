e<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {

            /**
             * Auto
             */

            $table->increments('id');
            $table->timestamps();

            /**
             * Main
             */

            $table->string('subject')->nullable();
            $table->text('body');
            $table->text('translated_body')->nullable();
            $table->boolean('auto_translate_reply')->default(1);
            $table->boolean('send_to_self')->default(0);
            $table->string('sender_email');
            $table->string('sender_name');

            /**
             * Relationships
             */

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('message_id')->nullable()->unsigned();
            $table->foreign('message_id')->references('id')->on('messages');

            $table->integer('lang_src_id')->unsigned();
            $table->foreign('lang_src_id')->references('id')->on('languages');

            $table->integer('lang_tgt_id')->unsigned();
            $table->foreign('lang_tgt_id')->references('id')->on('languages');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
