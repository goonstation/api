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
        Schema::create('job_bans', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id')->nullable();
            $table->integer('game_admin_id')->nullable();
            $table->text('server_id')->nullable();
            $table->text('ckey');
            $table->text('banned_from_job');
            $table->text('reason')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('job_bans');
    }
};
