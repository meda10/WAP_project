<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('title', function (Blueprint $table) {
            $table->id();
            $table->string('title_name');
            $table->integer('price');
            $table->string('cover_path');
            $table->string('description');
            $table->year('year');
            $table->enum('type', ['serial', 'movie']);
            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('title');
    }
}
