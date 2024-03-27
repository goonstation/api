<?php

use App\Models\Map;
use App\Models\MapLayer;
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
        Schema::create('map_layers', function (Blueprint $table) {
            $table->id();
            $table->integer('map_id');
            $table->integer('layer_id');
            $table->timestamps();

            $table->foreign('map_id')->references('id')->on('maps')->onDelete('cascade');
            $table->foreign('layer_id')->references('id')->on('maps');
        });

        $maps = Map::where('is_layer', false)->get();
        $debris = Map::where('map_id', 'DEBRIS')->first();
        foreach ($maps as $map) {
            if ($map->map_id === 'OSHAN' || $map->map_id === 'NADIR' || $map->map_id === 'POD_WARS') continue;
            $mapLayer = new MapLayer();
            $mapLayer->map_id = $map->id;
            $mapLayer->layer_id = $debris->id;
            $mapLayer->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('map_layers');
    }
};
