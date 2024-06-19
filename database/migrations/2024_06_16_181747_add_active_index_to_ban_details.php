<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE INDEX ban_details_ban_id_active_index ON ban_details USING btree (ban_id) WHERE (deleted_at IS NULL);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX ban_details_ban_id_active_index;');
    }
};
