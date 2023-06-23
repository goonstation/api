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
        Schema::create('game_rounds', function (Blueprint $table) {
            $table->id();
            $table->text('server_id');
            $table->text('map')->nullable();
            $table->text('game_type')->nullable();
            $table->boolean('rp_mode')->default(false);
            $table->boolean('crashed')->default(false);
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index('server_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_rounds');
    }
};
