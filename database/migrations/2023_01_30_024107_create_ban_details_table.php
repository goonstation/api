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
        Schema::create('ban_details', function (Blueprint $table) {
            $table->id();
            $table->integer('ban_id');
            $table->text('ckey')->nullable();
            $table->text('comp_id')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ban_id')->references('id')->on('bans');

            $table->index('ban_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ban_details');
    }
};
