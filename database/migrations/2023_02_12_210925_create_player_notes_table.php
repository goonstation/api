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
        Schema::create('player_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('player_id')->nullable();
            $table->text('ckey')->nullable();
            $table->integer('game_admin_id')->nullable();
            $table->text('server_id')->nullable();
            $table->integer('round_id')->nullable();
            $table->text('note');
            $table->jsonb('legacy_data')->nullable();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('game_admin_id')->references('id')->on('game_admins');
            $table->foreign('round_id')->references('id')->on('game_rounds');

            $table->index('player_id');
            $table->index('game_admin_id');
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
        Schema::dropIfExists('player_notes');
    }
};
