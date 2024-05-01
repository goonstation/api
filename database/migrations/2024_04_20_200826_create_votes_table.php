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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('voteable_id');
            $table->string('voteable_type');
            $table->ipAddress('ip');
            $table->tinyInteger('value');
            $table->timestamps();

            $table->index(['voteable_id', 'voteable_type']);

            $table->unique(['voteable_id', 'voteable_type', 'ip', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
