<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_receipts', function (Blueprint $table) {

            /**
             * Auto
             */

            $table->increments('id');
            $table->timestamps();

            /**
             * Main
             */

            $table->integer('cost_per_word');
            $table->integer('amount_charged');
            $table->boolean('reversed')->default(0);

            /**
             * Relationship
             */

            $table->integer('message_id')->unsigned();
            $table->foreign('message_id')->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_receipts');
    }
}
