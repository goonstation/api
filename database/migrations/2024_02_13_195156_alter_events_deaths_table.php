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
        Schema::table('events_deaths', function (Blueprint $table) {
            $table->float('bruteloss')->nullable()->change();
            $table->float('fireloss')->nullable()->change();
            $table->float('toxloss')->nullable()->change();
            $table->float('oxyloss')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_deaths', function (Blueprint $table) {
            $table->integer('bruteloss')->nullable()->change();
            $table->integer('fireloss')->nullable()->change();
            $table->integer('toxloss')->nullable()->change();
            $table->integer('oxyloss')->nullable()->change();
        });
    }
};
