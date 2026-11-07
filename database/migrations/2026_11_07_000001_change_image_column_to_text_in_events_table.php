<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE events ALTER COLUMN image TYPE TEXT;');
            DB::statement('ALTER TABLE events ALTER COLUMN image DROP NOT NULL;');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE events MODIFY image TEXT NULL;');
        } elseif ($driver === 'sqlite') {
            // SQLite handles type affinity dynamically; no table rebuild needed
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE events ALTER COLUMN image TYPE VARCHAR(255);');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE events MODIFY image VARCHAR(255) NULL;');
        }
    }
};
