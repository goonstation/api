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
        Schema::create('events_cyborg_module_selections', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');

            $table->integer('player_id')->nullable();
            $table->text('module')->nullable();
            $table->text('borg_type')->nullable();

            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('player_id')->references('id')->on('players');

            $table->index('round_id');
            $table->index('player_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_cyborg_module_selections');
    }
};
