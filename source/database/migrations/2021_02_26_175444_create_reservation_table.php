<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->dateTimeTz('reservation');
            $table->integer('reservation_period');
            $table->integer('fine');
            $table->integer('price');
            $table->integer('invoice_id');
            $table->boolean('returned');
            $table->boolean('paid');
            $table->boolean('issued');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('item_id')->references('id')->on('item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation');
    }
}
