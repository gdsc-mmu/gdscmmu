<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('club_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('student_id')->nullable()->index();
            $table->string('term')->index();
            $table->string('email')->nullable();
            $table->string('status')->default('active')->index(); // 'active' or 'expired'
            $table->timestamps();

            // Indexes for fast querying
            $table->index(['name', 'term']);
        });

        // Seed initial members from public/assets/2430_2530_Members.xlsx if it exists
        $excelPath = public_path('assets/2430_2530_Members.xlsx');
        if (file_exists($excelPath)) {
            try {
                $spreadsheet = IOFactory::load($excelPath);
                $rows = $spreadsheet->getActiveSheet()->toArray();

                if (!empty($rows)) {
                    $headers = array_map(function($h) {
                        return strtolower(trim((string)$h));
                    }, $rows[0]);

                    $termCol = array_search('registration terms', $headers);
                    if ($termCol === false) $termCol = array_search('term', $headers);
                    $nameCol = array_search('name', $headers);
                    $studentIdCol = array_search('student id', $headers);

                    $now = now();
                    $insertBatch = [];

                    for ($i = 1; $i < count($rows); $i++) {
                        $row = $rows[$i];
                        $name = $nameCol !== false && isset($row[$nameCol]) ? trim((string)$row[$nameCol]) : '';
                        $term = $termCol !== false && isset($row[$termCol]) ? trim((string)$row[$termCol]) : '2520';
                        $studentId = $studentIdCol !== false && isset($row[$studentIdCol]) ? trim((string)$row[$studentIdCol]) : null;

                        if (!empty($name)) {
                            $status = in_array($term, ['2430', '2330']) ? 'expired' : 'active';
                            $insertBatch[] = [
                                'name' => $name,
                                'student_id' => !empty($studentId) ? $studentId : null,
                                'term' => $term,
                                'email' => null,
                                'status' => $status,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];

                            if (count($insertBatch) >= 300) {
                                DB::table('club_members')->insert($insertBatch);
                                $insertBatch = [];
                            }
                        }
                    }

                    if (!empty($insertBatch)) {
                        DB::table('club_members')->insert($insertBatch);
                    }
                }
            } catch (\Throwable $e) {
                // If excel loading fails during migration, table is still created
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_members');
    }
};

