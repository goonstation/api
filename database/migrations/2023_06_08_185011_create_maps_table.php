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
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->text('map_id')->unique();
            $table->text('name');
            $table->boolean('active')->default(false);
            $table->boolean('is_layer')->default(false);
            $table->integer('tile_width')->default(300);
            $table->integer('tile_height')->default(300);
            $table->integer('screenshot_tiles')->default(30);
            $table->timestamp('last_built_at')->nullable();
            $table->integer('last_built_by')->nullable();
            $table->timestamps();

            $table->foreign('last_built_by')->references('id')->on('game_admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maps');
    }
};
