<?php

use App\Models\PlayerMedal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_medals_imported', function (Blueprint $table) {
            $table->id();
            $table->integer('player_id')->nullable();
            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players');
        });

        // get all the player ids that have any medals without a round_id
        // (which we're assuming came from a previous import from byond)
        // add imported records for all of them

        $playerIds = PlayerMedal::select(['players.id'])
            ->join('players', 'players.id', '=', 'player_medals.player_id')
            ->whereNull('player_medals.round_id')
            ->groupBy('players.id')
            ->get()
            ->map(function ($item) {
                return ['player_id' => $item->id];
            })
            ->toArray();

        DB::table('player_medals_imported')->insert($playerIds);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_medals_imported');
    }
};
