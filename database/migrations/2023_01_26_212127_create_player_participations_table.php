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
        Schema::create('player_participations', function (Blueprint $table) {
            $table->id();
            $table->integer('player_id');
            $table->integer('round_id')->nullable();
            $table->jsonb('legacy_data')->nullable();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('round_id')->references('id')->on('game_rounds');

            $table->index('player_id');
            $table->index('round_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_participations');
    }
};
