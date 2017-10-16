<?php

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

            /**
             * Relationships
             */

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('lang_src_id')->unsigned();
            $table->foreign('lang_src_id')->references('id')->on('languages');

            $table->integer('lang_tgt_id')->unsigned();
            $table->foreign('lang_tgt_id')->references('id')->on('languages');

            $table->integer('translation_status_id')->unsigned();
            $table->foreign('translation_status_id')->references('id')->on('translation_statuses');

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