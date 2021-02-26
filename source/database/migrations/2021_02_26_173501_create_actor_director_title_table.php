<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorDirectorTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actor_director_title', function (Blueprint $table) {
            $table->primary(['actor_director_id','title_id']);
            $table->bigInteger('actor_director_id')->unsigned();
            $table->bigInteger('title_id')->unsigned();
            $table->foreign('actor_director_id')->references('id')->on('actor_director');
            $table->foreign('title_id')->references('id')->on('title');
            $table->boolean('director');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actor_director_title');
    }
}
