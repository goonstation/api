<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::statement('ALTER TABLE events_station_names ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_station_names ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_ai_laws ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_ai_laws ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_bee_spawns ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_bee_spawns ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_deaths ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_deaths ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_fines ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_fines ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_tickets ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_tickets ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_gauntlet_high_scores ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_gauntlet_high_scores ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_antags ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_antags ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_antag_objectives ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_antag_objectives ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_antag_item_purchases ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_antag_item_purchases ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');

        DB::statement('ALTER TABLE events_logs ALTER COLUMN created_at TYPE timestamp(3) USING created_at::timestamp(3)');
        DB::statement('ALTER TABLE events_logs ALTER COLUMN updated_at TYPE timestamp(3) USING updated_at::timestamp(3)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE events_station_names ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_station_names ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_ai_laws ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_ai_laws ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_bee_spawns ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_bee_spawns ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_deaths ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_deaths ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_fines ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_fines ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_tickets ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_tickets ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_gauntlet_high_scores ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_gauntlet_high_scores ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_antags ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_antags ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_antag_objectives ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_antag_objectives ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_antag_item_purchases ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_antag_item_purchases ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');

        DB::statement('ALTER TABLE events_logs ALTER COLUMN created_at TYPE timestamp(0) USING created_at::timestamp(0)');
        DB::statement('ALTER TABLE events_logs ALTER COLUMN updated_at TYPE timestamp(0) USING updated_at::timestamp(0)');
    }
};
