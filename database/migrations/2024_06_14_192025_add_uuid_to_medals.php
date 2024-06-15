<?php

use App\Models\Medal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medals', function (Blueprint $table) {
            $table->uuid('uuid')->default(new Expression('gen_random_uuid()'));
        });

        DB::table('medals')->update([
            'uuid' => new Expression('gen_random_uuid()')
        ]);

        $medals = Medal::all();
        foreach ($medals as $medal) {
            $currentPath = "public/medals/{$medal->id}.png";
            if (Storage::exists($currentPath)) {
                Storage::move($currentPath, "public/medals/{$medal->uuid}.png");
            }
        }

        DB::statement("SELECT setval(pg_get_serial_sequence('medals', 'id'), coalesce(max(id), 0)+1 , false) FROM medals;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medals', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
