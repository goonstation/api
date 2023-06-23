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
        Schema::create('player_playtime', function (Blueprint $table) {
            $table->integer('player_id');
            $table->integer('seconds_played');
            $table->text('server_id');
            $table->timestamps();

            $table->unique(['player_id', 'server_id']);
            $table->foreign('player_id')->references('id')->on('players');

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
        Schema::dropIfExists('player_playtime');
    }
};
