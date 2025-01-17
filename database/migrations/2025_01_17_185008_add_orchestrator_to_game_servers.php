<?php

use App\Models\GameServer;
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
        Schema::table('game_servers', function (Blueprint $table) {
            $table->text('orchestrator')->nullable();
        });

        foreach (GameServer::all() as $server) {
            $server->orchestrator = 'http://192.168.0.2:8564';
            $server->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_servers', function (Blueprint $table) {
            $table->dropColumn('orchestrator');
        });
    }
};
