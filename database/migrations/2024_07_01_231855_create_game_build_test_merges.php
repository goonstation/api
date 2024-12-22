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
        Schema::create('game_build_test_merges', function (Blueprint $table) {
            $table->id();
            $table->integer('pr_id');
            $table->text('server_id');
            $table->integer('added_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->text('commit')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('game_servers');
            $table->foreign('added_by')->references('id')->on('game_admins');
            $table->foreign('updated_by')->references('id')->on('game_admins');

            $table->unique(['pr_id', 'server_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_build_test_merges');
    }
};
