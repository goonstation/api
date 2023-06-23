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
        Schema::create('player_saves', function (Blueprint $table) {
            $table->id();
            $table->integer('player_id');
            $table->text('name');
            $table->text('data')->nullable();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players');

            $table->unique(['player_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_saves');
    }
};
