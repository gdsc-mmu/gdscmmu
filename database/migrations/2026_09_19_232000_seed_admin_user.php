<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->updateOrInsert(
            ['name' => 'admin'],
            [
                'name' => 'admin',
                'email' => 'admin@gdscmmu.com',
                'password' => Hash::make('qwe123'),
                'user_type' => 'committee',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('name', 'admin')->delete();
    }
};
