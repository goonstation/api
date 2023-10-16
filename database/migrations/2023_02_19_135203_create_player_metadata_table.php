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
        Schema::create('player_metadata', function (Blueprint $table) {
            $table->id();
            $table->integer('player_id')->nullable();
            $table->text('metadata');
            $table->timestamps();

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
        Schema::dropIfExists('player_metadata');
    }
};
