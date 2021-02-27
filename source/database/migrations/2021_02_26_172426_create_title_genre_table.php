<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitleGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('title_genre', function (Blueprint $table) {
            $table->primary(['genre_id','title_id']);
            $table->bigInteger('genre_id')->unsigned();
            $table->bigInteger('title_id')->unsigned();
            $table->foreign('genre_id')->references('id')->on('genres');
            $table->foreign('title_id')->references('id')->on('titles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('title_genre');
    }
}
