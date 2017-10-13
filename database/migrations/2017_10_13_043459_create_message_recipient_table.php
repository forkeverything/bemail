<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_recipient', function (Blueprint $table) {

            /**
             * Auto
             */

            $table->timestamps();

            /**
             * Main
             */

            $table->integer('message_id')->unsigned();
            $table->integer('recipient_id')->unsigned();

            /**
             * Setup
             */

            $table->primary(['message_id', 'recipient_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_recipient');
    }
}
