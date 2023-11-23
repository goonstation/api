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
        Schema::create('player_connections', function (Blueprint $table) {
            $table->id();
            $table->integer('player_id');
            $table->integer('round_id')->nullable();
            $table->ipAddress('ip');
            $table->text('comp_id');
            $table->jsonb('legacy_data')->nullable();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('round_id')->references('id')->on('game_rounds');

            $table->index('player_id');
            $table->index('ip');
            $table->index('comp_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_connections');
    }
};
