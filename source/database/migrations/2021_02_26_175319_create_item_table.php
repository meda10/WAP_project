<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('language_id')->unsigned();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('title_id')->unsigned();
            $table->foreign('language_id')->references('id')->on('country');
            $table->foreign('store_id')->references('id')->on('store');
            $table->foreign('title_id')->references('id')->on('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item');
    }
}
