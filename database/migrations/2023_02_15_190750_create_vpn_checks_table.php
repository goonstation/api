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
        Schema::create('vpn_checks', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id')->nullable();
            $table->ipAddress('ip')->unique();
            $table->text('service');
            $table->text('response')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->foreign('round_id')->references('id')->on('game_rounds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vpn_checks');
    }
};
