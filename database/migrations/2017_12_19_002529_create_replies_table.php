<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {

            /**
             * Auto
             *
             */

            $table->increments('id');
            $table->timestamps();

            /**
             * Main
             */

            $table->string('sender_email');
            $table->string('sender_name')->nullable();

            /**
             * Relationships
             */

            $table->integer('original_message_id')->unsigned();
            $table->foreign('original_message_id')->references('id')->on('messages')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
