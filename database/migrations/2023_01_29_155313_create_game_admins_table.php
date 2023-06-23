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
        Schema::create('game_admins', function (Blueprint $table) {
            $table->id();
            $table->text('ckey');
            $table->text('name')->nullable();
            $table->text('discord_id')->nullable();
            $table->integer('rank_id')->nullable();
            $table->timestamps();

            $table->foreign('rank_id')->references('id')->on('game_admin_ranks');

            $table->unique('ckey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_admins');
    }
};
