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
        Schema::create('team_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_division_id')->constrained('team_divisions')->cascadeOnDelete();
            $table->string('name');
            $table->string('role');
            $table->string('email')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Seed initial GDGoC team structure
        $seedData = [
            [
                'division' => ['name' => 'Team Lead', 'description' => 'GDGoC Core Team 2025/2026', 'order' => 1],
                'members' => [
                    ['name' => 'Issye', 'role' => 'Team Lead', 'avatar' => 'issye2', 'order' => 1]
                ]
            ],
            [
                'division' => ['name' => 'Team Co-Lead', 'description' => 'We proofread, a lot', 'order' => 2],
                'members' => [
                    ['name' => 'Ammar', 'role' => 'Team Co-Lead', 'avatar' => 'ammar', 'order' => 1],
                    ['name' => 'Viinieesh', 'role' => 'Team Co-Lead', 'avatar' => 'viinieesh', 'order' => 2],
                ]
            ],
            [
                'division' => ['name' => 'Treasurer & Secretary', 'description' => 'We handle the money and the paperwork', 'order' => 3],
                'members' => [
                    ['name' => 'Ariana', 'role' => 'Treasurer', 'avatar' => 'ariana2', 'order' => 1],
                    ['name' => 'Intan', 'role' => 'Secretary', 'avatar' => 'intan2', 'order' => 2],
                ]
            ],
            [
                'division' => ['name' => 'Event Management', 'description' => 'We organize. You engage', 'order' => 4],
                'members' => [
                    ['name' => 'Bukhary', 'role' => 'Event Lead', 'avatar' => 'bukhary', 'order' => 1],
                    ['name' => 'Taseen', 'role' => 'Event Co-Lead', 'avatar' => 'taseen', 'order' => 2],
                    ['name' => 'Irdy', 'role' => 'Core Member', 'avatar' => 'irdy', 'order' => 3],
                    ['name' => 'Elysia', 'role' => 'Core Member', 'avatar' => 'elysia', 'order' => 4],
                ]
            ],
            [
                'division' => ['name' => 'Creativity & Publicity', 'description' => 'We design for the events!', 'order' => 5],
                'members' => [
                    ['name' => 'Hanani', 'role' => 'Creativity & Publicity Lead', 'avatar' => 'hanani', 'order' => 1],
                    ['name' => 'Liya', 'role' => 'Creativity & Publicity Co-Lead', 'avatar' => 'liya', 'order' => 2],
                    ['name' => 'Amirah', 'role' => 'Core Member', 'avatar' => 'amirah2', 'order' => 3],
                    ['name' => 'Shehab', 'role' => 'Core Member', 'avatar' => 'shehab', 'order' => 4],
                    ['name' => 'Vidhya', 'role' => 'Core Member', 'avatar' => 'vidhya', 'order' => 5],
                    ['name' => 'Yu Ning', 'role' => 'Core Member', 'avatar' => 'yuning', 'order' => 6],
                    ['name' => 'Nishant', 'role' => 'Core Member', 'avatar' => 'nishant', 'order' => 7],
                ]
            ],
            [
                'division' => ['name' => 'Sponsorship & Welfare', 'description' => 'We collaborate with others', 'order' => 6],
                'members' => [
                    ['name' => 'Danies', 'role' => 'Sponsorship & Welfare Lead', 'avatar' => 'danies', 'order' => 1],
                    ['name' => 'Agilan', 'role' => 'Sponsorship & Welfare Co-Lead', 'avatar' => 'agilan', 'order' => 2],
                    ['name' => 'Che Ammar', 'role' => 'Core Member', 'avatar' => 'cheammar', 'order' => 3],
                    ['name' => 'Hamizah', 'role' => 'Core Member', 'avatar' => 'hamizah', 'order' => 4],
                    ['name' => 'Losha', 'role' => 'Core Member', 'avatar' => 'losha', 'order' => 5],
                ]
            ],
            [
                'division' => ['name' => 'Cybersecurity Team', 'description' => 'We hack, we tech', 'order' => 7],
                'members' => [
                    ['name' => 'Danish', 'role' => 'Cybersecurity Lead', 'avatar' => 'danish', 'order' => 1],
                    ['name' => 'Satishwaran', 'role' => 'Cybersecurity Co-Lead', 'avatar' => 'satish', 'order' => 2],
                    ['name' => 'Prabu', 'role' => 'Core Member', 'avatar' => 'prabu', 'order' => 3],
                    ['name' => 'Afham', 'role' => 'Core Member', 'avatar' => 'afham', 'order' => 4],
                ]
            ],
            [
                'division' => ['name' => 'Software Engineering Team', 'description' => 'We code , develop , test our software all the time!', 'order' => 8],
                'members' => [
                    ['name' => 'Raziq', 'role' => 'Software Engineering Lead', 'avatar' => 'raziq', 'order' => 1],
                    ['name' => 'Aleessa', 'role' => 'Software Engineering Co-Lead', 'avatar' => 'aleessa', 'order' => 2],
                    ['name' => 'Liou Zi', 'role' => 'Core Member', 'avatar' => 'liouzi', 'order' => 3],
                    ['name' => 'Yusof', 'role' => 'Core Member', 'avatar' => 'yusof', 'order' => 4],
                ]
            ],
            [
                'division' => ['name' => 'AI/ML Team', 'description' => 'We advocate AI/ML to students', 'order' => 9],
                'members' => [
                    ['name' => 'Rime', 'role' => 'AI/ML Lead', 'avatar' => 'rime', 'order' => 1],
                    ['name' => 'Prita', 'role' => 'AI/ML Co-Lead', 'avatar' => 'prita', 'order' => 2],
                    ['name' => 'Yong', 'role' => 'Core Member', 'avatar' => 'yong', 'order' => 3],
                    ['name' => 'Raghad', 'role' => 'Core Member', 'avatar' => 'raghad', 'order' => 4],
                ]
            ],
        ];

        $now = now();
        foreach ($seedData as $item) {
            $divisionId = DB::table('team_divisions')->insertGetId([
                'name' => $item['division']['name'],
                'description' => $item['division']['description'],
                'order' => $item['division']['order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($item['members'] as $mem) {
                DB::table('team_members')->insert([
                    'team_division_id' => $divisionId,
                    'name' => $mem['name'],
                    'role' => $mem['role'],
                    'avatar' => $mem['avatar'] ?? null,
                    'email' => strtolower(str_replace(' ', '', $mem['name'])) . '@gdgmmu.com',
                    'order' => $mem['order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('team_divisions');
    }
};
