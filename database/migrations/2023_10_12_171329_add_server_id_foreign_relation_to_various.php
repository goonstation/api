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
        Schema::table('game_rounds', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('game_servers');
        });

        Schema::table('player_playtime', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('game_servers');
        });

        Schema::table('bans', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('game_servers');
        });

        Schema::table('player_notes', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('game_servers');
        });

        Schema::table('job_bans', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('game_servers');
        });

        Schema::table('map_switches', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('game_servers');
        });

        Schema::table('players_online', function (Blueprint $table) {
            $table->foreign('server_id')->references('server_id')->on('game_servers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_rounds', function (Blueprint $table) {
            $table->dropForeign('server_id');
        });

        Schema::table('player_playtime', function (Blueprint $table) {
            $table->dropForeign('server_id');
        });

        Schema::table('bans', function (Blueprint $table) {
            $table->dropForeign('server_id');
        });

        Schema::table('player_notes', function (Blueprint $table) {
            $table->dropForeign('server_id');
        });

        Schema::table('job_bans', function (Blueprint $table) {
            $table->dropForeign('server_id');
        });

        Schema::table('map_switches', function (Blueprint $table) {
            $table->dropForeign('server_id');
        });

        Schema::table('players_online', function (Blueprint $table) {
            $table->dropForeign('server_id');
        });
    }
};
