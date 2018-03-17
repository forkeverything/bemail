<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            /**
             * Auto
             */
            $table->timestamps();

            /**
             * Main
             */

            $table->integer('id')->unsigned()->unique();
            $table->integer('unit_count');
            $table->integer('unit_price');

            /**
             * Relationship
             */

            $table->integer('message_id')->unsigned()->unique();
            $table->foreign('message_id')->references('id')->on('messages');

            $table->integer('order_status_id')->unsigned();
            $table->foreign('order_status_id')->references('id')->on('order_statuses');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
