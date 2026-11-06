<?php

namespace App\Http\Controllers;

use App\Models\ClubMember;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClubMemberController extends Controller
{
    /**
     * Display the Manage Members admin page.
     */
    public function index(Request $request)
    {
        $query = ClubMember::query();

        // Search by Name, Student ID, or Email
        if ($search = trim((string)$request->input('search'))) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Term
        if ($term = trim((string)$request->input('term'))) {
            if ($term !== 'all') {
                $query->where('term', $term);
            }
        }

        // Filter by Status (active / expired)
        if ($status = trim((string)$request->input('status'))) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortOrder = $request->input('order', 'desc');
        if (in_array($sortField, ['name', 'student_id', 'term', 'status', 'created_at'])) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $members = $query->paginate(30)->withQueryString();

        // Summary Statistics
        $totalMembers = ClubMember::count();
        $activeMembers = ClubMember::where('status', 'active')->count();
        $inactiveMembers = ClubMember::whereIn('status', ['inactive', 'expired'])->count();
        $distinctTerms = ClubMember::select('term')->distinct()->orderBy('term', 'desc')->pluck('term');
        $termsWithCounts = ClubMember::selectRaw("
            term, 
            count(*) as count,
            sum(case when status = 'active' then 1 else 0 end) as active_count,
            sum(case when status != 'active' then 1 else 0 end) as inactive_count
        ")
        ->groupBy('term')
        ->orderBy('term', 'desc')
        ->get();

        return view('admin.members.index', compact(
            'members',
            'totalMembers',
            'activeMembers',
            'inactiveMembers',
            'distinctTerms',
            'termsWithCounts'
        ));
    }

    /**
     * Import Excel spreadsheet and auto-update database based on name, student id, and term.
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'default_term' => 'nullable|string|max:50',
        ]);

        $file = $request->file('excel_file');
        $defaultTerm = trim((string)$request->input('default_term', '2530'));
        if (empty($defaultTerm)) {
            $defaultTerm = '2530';
        }

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (empty($rows) || count($rows) < 2) {
                return redirect()->back()->with('error', 'The uploaded Excel file is empty or missing data rows.');
            }

            // Dynamically scan for the actual header row (handles metadata, cards like "Total Active 592", or blank top rows)
            $headerRowIndex = false;
            $nameIndex = false;
            $studentIdIndex = false;
            $termIndex = false;
            $emailIndex = false;

            $scanLimit = min(20, count($rows));
            for ($r = 0; $r < $scanLimit; $r++) {
                $candidateHeaders = array_map(function($h) {
                    return strtolower(preg_replace('/\s+/', ' ', trim((string)$h)));
                }, $rows[$r]);

                $tempNameIdx = false;
                $tempStudentIdIdx = false;
                $tempTermIdx = false;
                $tempEmailIdx = false;

                foreach ($candidateHeaders as $idx => $cell) {
                    if (in_array($cell, ['name', 'full name', 'student name', 'member name', 'nama'])) {
                        $tempNameIdx = $idx;
                    } elseif (in_array($cell, ['student id', 'student_id', 'id', 'student number', 'matric no', 'matric_no', 'student no', 'matric'])) {
                        $tempStudentIdIdx = $idx;
                    } elseif (in_array($cell, ['registration term', 'registration terms', 'term', 'terms', 'session', 'cohort'])) {
                        $tempTermIdx = $idx;
                    } elseif (in_array($cell, ['email', 'student email', 'e-mail'])) {
                        $tempEmailIdx = $idx;
                    }
                }

                // If this row contains "Name" along with either "Student ID" or "Registration Term"
                if ($tempNameIdx !== false && ($tempStudentIdIdx !== false || $tempTermIdx !== false)) {
                    $headerRowIndex = $r;
                    $nameIndex = $tempNameIdx;
                    $studentIdIndex = $tempStudentIdIdx;
                    $termIndex = $tempTermIdx;
                    $emailIndex = $tempEmailIdx;
                    break;
                }
            }

            // Fallback: check if any row has either Name or Student ID
            if ($headerRowIndex === false) {
                for ($r = 0; $r < $scanLimit; $r++) {
                    $candidateHeaders = array_map(function($h) {
                        return strtolower(preg_replace('/\s+/', ' ', trim((string)$h)));
                    }, $rows[$r]);

                    foreach ($candidateHeaders as $idx => $cell) {
                        if ($nameIndex === false && in_array($cell, ['name', 'full name', 'student name', 'member name', 'nama'])) {
                            $nameIndex = $idx;
                        }
                        if ($studentIdIndex === false && in_array($cell, ['student id', 'student_id', 'id', 'student number', 'matric no', 'matric_no', 'student no', 'matric'])) {
                            $studentIdIndex = $idx;
                        }
                        if ($termIndex === false && in_array($cell, ['registration term', 'registration terms', 'term', 'terms', 'session', 'cohort'])) {
                            $termIndex = $idx;
                        }
                    }

                    if ($nameIndex !== false || $studentIdIndex !== false) {
                        $headerRowIndex = $r;
                        break;
                    }
                }
            }

            if ($headerRowIndex === false || ($nameIndex === false && $studentIdIndex === false)) {
                return redirect()->back()->with('error', 'Could not detect the header row containing "Name" and "Student ID" columns in your Excel file. Please verify column headers.');
            }

            $createdCount = 0;
            $filledCount = 0;
            $duplicatesIgnored = 0;
            $skippedCount = 0;
            $now = now();
            $currentTerm = $defaultTerm;

            // Process data rows starting immediately after the detected header row
            for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // If the term column has a value on this row, switch active cohort term
                $rowTerm = $termIndex !== false && isset($row[$termIndex]) ? trim((string)$row[$termIndex]) : '';
                if ($rowTerm !== '') {
                    $currentTerm = $rowTerm;
                }

                $rawName = $nameIndex !== false && isset($row[$nameIndex]) ? trim((string)$row[$nameIndex]) : '';
                // Normalize spaces in name (e.g. collapse multiple internal spaces)
                $name = preg_replace('/\s+/', ' ', $rawName);
                $normalizedIncomingName = strtolower($name);

                $rawStudentId = $studentIdIndex !== false && isset($row[$studentIdIndex]) ? trim((string)$row[$studentIdIndex]) : null;
                $studentId = ($rawStudentId !== null && in_array(trim($rawStudentId), ['?', '-', 'N/A', 'n/a', ''])) ? null : $rawStudentId;
                if ($studentId !== null) {
                    $studentId = trim($studentId);
                }

                $term = $currentTerm;
                $email = $emailIndex !== false && isset($row[$emailIndex]) && trim((string)$row[$emailIndex]) !== ''
                    ? trim((string)$row[$emailIndex])
                    : null;

                // Skip rows where both name and student_id are empty
                if (empty($name) && empty($studentId)) {
                    $skippedCount++;
                    continue;
                }

                // Default name fallback if only studentId provided
                if (empty($name) && !empty($studentId)) {
                    $name = 'Student ' . $studentId;
                    $normalizedIncomingName = strtolower($name);
                }

                $status = in_array($term, ['2430', '2330', '2230']) ? 'expired' : 'active';

                // Duplicate checking and flexible matching
                $existing = null;

                // 1. Match by Student ID (if incoming row has a valid ID)
                if (!empty($studentId)) {
                    $existing = ClubMember::where('student_id', $studentId)
                        ->where('term', $term)
                        ->first();

                    if (!$existing) {
                        $existing = ClubMember::where('student_id', $studentId)->first();
                    }
                }

                // 2. Match by Name (case-insensitive & trimmed)
                if (!$existing && !empty($normalizedIncomingName)) {
                    // Try exact/case-insensitive match within same term
                    $existing = ClubMember::where('term', $term)
                        ->where(function($q) use ($name, $normalizedIncomingName) {
                            $q->where('name', $name)
                              ->orWhereRaw('LOWER(TRIM(name)) = ?', [$normalizedIncomingName]);
                        })
                        ->first();

                    // If not found in same term, match by name across any term
                    if (!$existing) {
                        $existing = ClubMember::where(function($q) use ($name, $normalizedIncomingName) {
                            $q->where('name', $name)
                              ->orWhereRaw('LOWER(TRIM(name)) = ?', [$normalizedIncomingName]);
                        })->first();
                    }
                }

                if ($existing) {
                    // Duplicate found: Only fill in whatever is missing, without overwriting existing data
                    $updates = [];

                    // 1. Fill missing Student ID (e.g. first record had no ID, imported row has ID)
                    $existingId = $existing->student_id !== null ? trim($existing->student_id) : '';
                    $hasExistingId = $existingId !== '' && !in_array($existingId, ['?', '-', 'N/A', 'n/a']);
                    if (!$hasExistingId && !empty($studentId)) {
                        $updates['student_id'] = $studentId;
                    }

                    // 2. Fill missing or generic Name
                    $existingName = trim($existing->name);
                    $isGenericName = empty($existingName) || str_starts_with($existingName, 'Student ');
                    if ($isGenericName && !empty($name) && !str_starts_with($name, 'Student ')) {
                        $updates['name'] = $name;
                    }

                    // 3. Fill missing Email
                    if (empty($existing->email) && !empty($email)) {
                        $updates['email'] = $email;
                    }

                    // 4. Fill missing Term if existing is empty
                    if (empty($existing->term) && !empty($term)) {
                        $updates['term'] = $term;
                    }

                    // If anything missing was completed, save updates
                    if (!empty($updates)) {
                        $updates['updated_at'] = $now;
                        $existing->update($updates);
                        $filledCount++;
                    } else {
                        // Complete duplicate with no new missing fields to fill: do nothing
                        $duplicatesIgnored++;
                    }
                } else {
                    // New unique member: insert record
                    ClubMember::create([
                        'name' => $name,
                        'student_id' => !empty($studentId) ? $studentId : null,
                        'term' => $term,
                        'email' => $email,
                        'status' => $status,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $createdCount++;
                }
            }

            $totalProcessed = $createdCount + $filledCount + $duplicatesIgnored;
            $msgParts = [];
            if ($createdCount > 0) {
                $msgParts[] = "{$createdCount} new member(s) added";
            }
            if ($filledCount > 0) {
                $msgParts[] = "{$filledCount} record(s) completed with missing data (e.g. IDs filled)";
            }
            if ($duplicatesIgnored > 0) {
                $msgParts[] = "{$duplicatesIgnored} duplicate(s) verified";
            }
            $summaryText = !empty($msgParts) ? implode(', ', $msgParts) : "No changes detected";

            return redirect()->route('admin.club_members.index')->with(
                'message',
                "Excel sync complete! {$summaryText} ({$totalProcessed} total rows processed)."
            );
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error reading Excel file: ' . $e->getMessage());
        }
    }

    /**
     * Store a single member manually.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'nullable|string|max:100',
            'term' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive,expired',
        ]);

        ClubMember::create($validated);

        return redirect()->route('admin.club_members.index')->with('message', "Member '{$validated['name']}' created successfully!");
    }

    /**
     * Update an existing member record.
     */
    public function update(Request $request, ClubMember $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'nullable|string|max:100',
            'term' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive,expired',
        ]);

        $member->update($validated);

        return redirect()->route('admin.club_members.index')->with('message', "Member '{$member->name}' updated!");
    }

    /**
     * Delete a member record.
     */
    public function destroy(ClubMember $member)
    {
        $name = $member->name;
        $member->delete();

        return redirect()->route('admin.club_members.index')->with('message', "Member '{$name}' deleted.");
    }

    /**
     * Archive all members belonging to a specified term (sets status to 'inactive').
     * Inactive members will not be displayed in the public member tracker search.
     */
    public function archiveTerm(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string',
        ]);

        $term = trim($validated['term']);
        $count = ClubMember::where('term', $term)->count();

        if ($count === 0) {
            return redirect()->back()->with('error', "No members found under Term '{$term}'.");
        }

        ClubMember::where('term', $term)->update(['status' => 'inactive']);

        return redirect()->route('admin.club_members.index')->with(
            'message',
            "Term '{$term}' archived successfully! {$count} member records are now set to Inactive and will NOT be displayed in the public member tracker search."
        );
    }

    /**
     * Reactivate/Unarchive all members belonging to a specified term (sets status to 'active').
     */
    public function activateTerm(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string',
        ]);

        $term = trim($validated['term']);
        $count = ClubMember::where('term', $term)->count();

        if ($count === 0) {
            return redirect()->back()->with('error', "No members found under Term '{$term}'.");
        }

        ClubMember::where('term', $term)->update(['status' => 'active']);

        return redirect()->route('admin.club_members.index')->with(
            'message',
            "Term '{$term}' reactivated! {$count} members have been restored to Active status and are now visible in the member tracker search."
        );
    }

    /**
     * Bulk delete all members belonging to a specified term.
     */
    public function deleteByTerm(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string',
        ]);

        $term = trim($validated['term']);
        $count = ClubMember::where('term', $term)->count();

        if ($count === 0) {
            return redirect()->back()->with('error', "No members found under Term '{$term}'.");
        }

        ClubMember::where('term', $term)->delete();

        return redirect()->route('admin.club_members.index')->with(
            'message',
            "Bulk delete complete! Successfully removed all {$count} member records belonging to Term '{$term}'."
        );
    }

    /**
     * Bulk delete selected members via checkboxes.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'No members were selected for bulk deletion.');
        }

        $count = ClubMember::whereIn('id', $ids)->count();
        ClubMember::whereIn('id', $ids)->delete();

        return redirect()->route('admin.club_members.index')->with(
            'message',
            "Bulk delete complete! Successfully deleted {$count} selected member records."
        );
    }

    /**
     * Export members list to an Excel spreadsheet with range filters.
     */
    public function export(Request $request)
    {
        $query = ClubMember::query();

        // Range Filter: Term Cohort
        $term = trim((string)$request->input('term', 'all'));
        if ($term !== '' && $term !== 'all') {
            $query->where('term', $term);
        }

        // Range Filter: Membership Status
        $status = trim((string)$request->input('status', 'all'));
        if ($status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        // Range Filter: Search Keyword
        if ($search = trim((string)$request->input('search'))) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Range Filter: Date Added (date_from and date_to)
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Ordering: group by term chronologically, then by name
        $query->orderBy('term', 'asc')->orderBy('name', 'asc');

        // Range Filter: Record Limit
        $limit = (int)$request->input('limit', 0);
        if ($limit > 0) {
            $query->limit($limit);
        }

        $members = $query->get();

        if ($members->isEmpty()) {
            return redirect()->back()->with('error', 'No member records matched the specified export range criteria.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('GDGoC Members');

        // Headers: Registration Term | Name | Student ID
        $sheet->setCellValue('A1', 'Registration Term');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Student ID');

        // Style Header Row
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $rowNum = 2;
        $lastTerm = null;
        foreach ($members as $m) {
            // Write term on the first row of each cohort group, leave blank for following members
            if ($m->term !== $lastTerm) {
                $sheet->setCellValue('A' . $rowNum, $m->term);
                $lastTerm = $m->term;
            } else {
                $sheet->setCellValue('A' . $rowNum, '');
            }

            $sheet->setCellValue('B' . $rowNum, $m->name);
            $sheet->setCellValue('C' . $rowNum, $m->student_id ?? '');
            $rowNum++;
        }

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(20);

        $termTag = ($term !== '' && $term !== 'all') ? "_Term{$term}" : '';
        $fileName = 'GDGoC_MMU_Members' . $termTag . '_' . date('Ymd_His') . '.xlsx';

        return new StreamedResponse(function() use ($spreadsheet) {
            $xlsxWriter = new Xlsx($spreadsheet);
            $xlsxWriter->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Download a blank Excel import template matching the expected grouped structure.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template');

        $sheet->setCellValue('A1', 'Registration Term');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Student ID');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Sample rows matching the exact grouping structure
        $sampleRows = [
            ['2510', 'NISHANT', '243UC247DF'],
            ['',     'Liw Jin Han', '243UC247K8'],
            ['',     'PRABU NATAR A/L DHARMENDRA', '243UC2463Z'],
            ['2520', 'LEE KAI SHUEN', '252FC2525K'],
            ['',     'Shamkumar', '242UC244NQ'],
            ['2530', 'Muhammad Amir Rahimi Bin Muhammad Assuad', '242UC244G4'],
            ['',     'KHALID HOSHAM OMER MOHAMED', '253FC255ZU'],
            ['2610', 'NUR DAMIA\' BATRISYIA BINTI MOHAMMAD DENEE ROSDI', '252UC243Y5'],
            ['',     'Qaisarah binti Shamsul Azran', '252UC243DY'],
        ];

        $rowNum = 2;
        foreach ($sampleRows as $row) {
            $sheet->setCellValue('A' . $rowNum, $row[0]);
            $sheet->setCellValue('B' . $rowNum, $row[1]);
            $sheet->setCellValue('C' . $rowNum, $row[2]);
            $rowNum++;
        }

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(20);

        $fileName = 'GDGoC_Members_Import_Template.xlsx';

        return new StreamedResponse(function() use ($spreadsheet) {
            $xlsxWriter = new Xlsx($spreadsheet);
            $xlsxWriter->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}

