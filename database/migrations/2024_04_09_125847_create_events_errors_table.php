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
        Schema::create('events_errors', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id');
            $table->text('name')->nullable();
            $table->text('file')->nullable();
            $table->text('line')->nullable();
            $table->text('desc')->nullable();
            $table->text('user')->nullable();
            $table->text('user_ckey')->nullable();
            $table->boolean('invalid')->default(false);
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
        Schema::dropIfExists('events_errors');
    }
};
