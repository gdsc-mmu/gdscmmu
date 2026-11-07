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
            DB::statement('ALTER TABLE team_members ALTER COLUMN avatar TYPE TEXT;');
            DB::statement('ALTER TABLE team_members ALTER COLUMN avatar DROP NOT NULL;');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE team_members MODIFY avatar TEXT NULL;');
        } elseif ($driver === 'sqlite') {
            // SQLite has dynamic typing; no-op
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE team_members ALTER COLUMN avatar TYPE VARCHAR(255);');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE team_members MODIFY avatar VARCHAR(255) NULL;');
        }
    }
};

