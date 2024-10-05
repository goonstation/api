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
        Schema::create('game_builds', function (Blueprint $table) {
            $table->id();
            $table->text('server_id');
            $table->integer('started_by')->nullable();
            $table->text('branch')->nullable();
            $table->text('commit')->nullable();
            $table->text('map')->nullable();
            $table->boolean('failed')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->timestamps();
            $table->timestamp('ended_at')->nullable();

            $table->foreign('server_id')->references('server_id')->on('game_servers');
            $table->foreign('started_by')->references('id')->on('game_admins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_builds');
    }
};
