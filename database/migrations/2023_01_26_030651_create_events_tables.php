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
        Schema::create('events_station_names', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->text('name')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');

            $table->index('round_id');
        });

        Schema::create('events_ai_laws', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->integer('player_id')->nullable();
            $table->text('ai_name')->nullable();
            $table->smallInteger('law_number')->nullable();
            $table->text('law_text')->nullable();
            $table->text('uploader_name')->nullable();
            $table->text('uploader_job')->nullable();
            $table->text('uploader_ckey')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('player_id')->references('id')->on('players');

            $table->index('round_id');
            $table->index('player_id');
        });

        Schema::create('events_bee_spawns', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->text('name')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');

            $table->index('round_id');
        });

        Schema::create('events_deaths', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->integer('player_id')->nullable();
            $table->text('mob_name')->nullable();
            $table->text('mob_job')->nullable();
            $table->smallInteger('x')->nullable();
            $table->smallInteger('y')->nullable();
            $table->smallInteger('z')->nullable();
            $table->integer('bruteloss')->nullable();
            $table->integer('fireloss')->nullable();
            $table->integer('toxloss')->nullable();
            $table->integer('oxyloss')->nullable();
            $table->boolean('gibbed')->nullable();
            $table->text('last_words')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('player_id')->references('id')->on('players');

            $table->index('round_id');
            $table->index('player_id');
        });

        Schema::create('events_fines', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->integer('player_id')->nullable();
            $table->text('target')->nullable();
            $table->text('reason')->nullable();
            $table->text('issuer')->nullable();
            $table->text('issuer_job')->nullable();
            $table->text('issuer_ckey')->nullable();
            $table->integer('amount')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('player_id')->references('id')->on('players');

            $table->index('round_id');
            $table->index('player_id');
        });

        Schema::create('events_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->integer('player_id')->nullable();
            $table->text('target')->nullable();
            $table->text('reason')->nullable();
            $table->text('issuer')->nullable();
            $table->text('issuer_job')->nullable();
            $table->text('issuer_ckey')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('player_id')->references('id')->on('players');

            $table->index('round_id');
            $table->index('player_id');
        });

        Schema::create('events_gauntlet_high_scores', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->text('names')->nullable();
            $table->integer('score')->nullable();
            $table->integer('highest_wave')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');

            $table->index('round_id');
        });

        Schema::create('events_antags', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->integer('player_id')->nullable();
            $table->text('mob_name')->nullable();
            $table->text('mob_job')->nullable();
            $table->text('traitor_type')->nullable();
            $table->text('special')->nullable();
            $table->text('late_joiner')->nullable();
            $table->boolean('success')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('player_id')->references('id')->on('players');

            $table->index('round_id');
            $table->index('player_id');
        });

        Schema::create('events_antag_objectives', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->integer('player_id')->nullable();
            $table->text('objective')->nullable();
            $table->boolean('success')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('player_id')->references('id')->on('players');

            $table->index('round_id');
            $table->index('player_id');
        });

        Schema::create('events_antag_item_purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->integer('player_id')->nullable();
            $table->text('mob_name')->nullable();
            $table->text('mob_job')->nullable();
            $table->smallInteger('x')->nullable();
            $table->smallInteger('y')->nullable();
            $table->smallInteger('z')->nullable();
            $table->text('item')->nullable();
            $table->integer('cost')->nullable();
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
        Schema::dropIfExists('events_station_names');
        Schema::dropIfExists('events_ai_laws');
        Schema::dropIfExists('events_bee_spawns');
        Schema::dropIfExists('events_deaths');
        Schema::dropIfExists('events_fines');
        Schema::dropIfExists('events_tickets');
        Schema::dropIfExists('events_gauntlet_high_scores');
        Schema::dropIfExists('events_antags');
        Schema::dropIfExists('events_antag_objectives');
        Schema::dropIfExists('events_antag_item_purchases');
    }
};
