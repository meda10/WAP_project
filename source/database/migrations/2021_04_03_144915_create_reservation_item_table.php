<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_item', function (Blueprint $table) {
            $table->primary(['item_id','reservation_id']);
            $table->bigInteger('item_id')->unsigned();
            $table->bigInteger('reservation_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_item');
    }
}
