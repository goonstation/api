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
        Schema::create('events_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->text('type')->nullable();
            $table->text('source')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');

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
        Schema::dropIfExists('events_logs');
    }
};
