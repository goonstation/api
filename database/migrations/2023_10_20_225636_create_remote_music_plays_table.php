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
        Schema::create('remote_music_plays', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->integer('round_id');
            $table->integer('game_admin_id')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
            $table->foreign('game_admin_id')->references('id')->on('game_admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remote_music_plays');
    }
};
