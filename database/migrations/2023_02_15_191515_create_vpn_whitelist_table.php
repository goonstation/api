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
        Schema::create('vpn_whitelist', function (Blueprint $table) {
            $table->id();
            $table->integer('game_admin_id');
            $table->text('ckey')->unique();
            $table->timestamps();

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
        Schema::dropIfExists('vpn_whitelist');
    }
};
