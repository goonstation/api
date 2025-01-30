<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_build_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('build_id')->index();
            $table->text('type')->nullable();
            $table->text('group')->nullable();
            $table->text('log');
            $table->timestamps();

            $table->foreign('build_id')->references('id')->on('game_builds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_build_logs');
    }
};
