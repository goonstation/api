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
            $table->float('bruteloss')->change();
            $table->float('fireloss')->change();
            $table->float('toxloss')->change();
            $table->float('oxyloss')->change();
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
            $table->integer('bruteloss')->change();
            $table->integer('fireloss')->change();
            $table->integer('toxloss')->change();
            $table->integer('oxyloss')->change();
        });
    }
};
