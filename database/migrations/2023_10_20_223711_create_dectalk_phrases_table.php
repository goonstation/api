<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dectalk_phrases', function (Blueprint $table) {
            $table->id();
            $table->text('phrase');
            $table->integer('round_id');
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dectalk_phrases');
    }
};
