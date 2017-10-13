<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            /**
             * Auto
             */

            $table->increments('id');
            $table->timestamps();
            $table->rememberToken();

            /**
             * Main
             */
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('word_credits')->default(0);

            /**
             * Relationships
             */

            $table->integer('language_id')->unsigned();
            $table->foreign('language_id')->references('id')->on('languages');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
