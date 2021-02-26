<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_title', function (Blueprint $table) {
            $table->primary(['participant_id','title_id']);
            $table->bigInteger('participant_id')->unsigned();
            $table->bigInteger('title_id')->unsigned();
            $table->foreign('participant_id')->references('id')->on('participants');
            $table->foreign('title_id')->references('id')->on('titles');
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
        Schema::dropIfExists('participant_title');
    }
}
