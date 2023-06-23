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
        Schema::create('map_switches', function (Blueprint $table) {
            $table->id();
            $table->integer('game_admin_id')->nullable();
            $table->integer('round_id')->nullable();
            $table->text('server_id')->nullable();
            $table->text('map');
            $table->integer('votes')->default(0);
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
        Schema::dropIfExists('map_switches');
    }
};
