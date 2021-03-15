<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_reservation', function (Blueprint $table) {
            $table->primary(['discount_id','reservation_id']);
            $table->bigInteger('discount_id')->unsigned();
            $table->bigInteger('reservation_id')->unsigned();
            $table->foreign('discount_id')->references('id')->on('discounts');
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
        Schema::dropIfExists('discount_reservation');
    }
}
