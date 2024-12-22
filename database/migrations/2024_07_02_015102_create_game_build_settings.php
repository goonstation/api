<?php

use App\Models\GameBuildSetting;
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
        Schema::create('game_build_settings', function (Blueprint $table) {
            $table->id();
            $table->text('server_id');
            $table->text('branch')->default('master');
            $table->smallInteger('byond_major');
            $table->smallInteger('byond_minor');
            $table->text('rustg_version');
            $table->boolean('rp_mode')->default(false);
            $table->text('map_id')->nullable();
            $table->timestamps();

            $table->foreign('server_id')->references('server_id')->on('game_servers');
            $table->foreign('map_id')->references('map_id')->on('maps');
        });

        $servers = GameServer::all();
        foreach ($servers as $server) {
            $setting = new GameBuildSetting;
            $setting->server_id = $server->server_id;
            if ($server->server_id === 'dev') {
                $setting->branch = 'develop';
            }
            $setting->byond_major = 515;
            $setting->byond_minor = 1637;
            $setting->rustg_version = 'v3.3.0-G';
            if (in_array($server->server_id, ['main3', 'main4', 'main5'])) {
                $setting->rp_mode = true;
            }
            $setting->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_build_settings');
    }
};
