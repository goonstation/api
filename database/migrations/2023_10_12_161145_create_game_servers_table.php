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
        Schema::create('game_servers', function (Blueprint $table) {
            $table->id();
            $table->text('server_id')->unique();
            $table->text('name');
            $table->text('short_name');
            $table->text('address');
            $table->integer('port');
            $table->boolean('active')->default(false);
            $table->boolean('invisible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_servers');
    }
};
