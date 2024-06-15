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
        Schema::table('player_medals', function (Blueprint $table) {
            $table->dropForeign('player_medals_medal_id_foreign');
            $table->foreign('medal_id')->references('id')->on('medals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_medals', function (Blueprint $table) {
            $table->dropForeign('player_medals_medal_id_foreign');
            $table->foreign('medal_id')->references('id')->on('medals');
        });
    }
};
