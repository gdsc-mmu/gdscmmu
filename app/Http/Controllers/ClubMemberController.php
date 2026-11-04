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

            // Normalize header columns
            $headers = array_map(function($h) {
                return strtolower(trim((string)$h));
            }, $rows[0]);

            // Locate column indices flexibly
            $nameIndex = false;
            $studentIdIndex = false;
            $termIndex = false;
            $emailIndex = false;

            foreach ($headers as $idx => $header) {
                if (in_array($header, ['name', 'full name', 'student name', 'member name', 'nama'])) {
                    $nameIndex = $idx;
                } elseif (in_array($header, ['student id', 'student_id', 'id', 'student number', 'matric no', 'matric_no', 'student no', 'matric'])) {
                    $studentIdIndex = $idx;
                } elseif (in_array($header, ['term', 'registration terms', 'registration term', 'session', 'cohort', 'terms'])) {
                    $termIndex = $idx;
                } elseif (in_array($header, ['email', 'student email', 'e-mail'])) {
                    $emailIndex = $idx;
                }
            }

            if ($nameIndex === false && $studentIdIndex === false) {
                return redirect()->back()->with('error', 'Could not detect a "Name" or "Student ID" column in the Excel header row. Please check your file formatting.');
            }

            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $now = now();

            // Process data rows
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                $name = $nameIndex !== false && isset($row[$nameIndex]) ? trim((string)$row[$nameIndex]) : '';
                $studentId = $studentIdIndex !== false && isset($row[$studentIdIndex]) ? trim((string)$row[$studentIdIndex]) : null;
                $term = $termIndex !== false && isset($row[$termIndex]) && trim((string)$row[$termIndex]) !== ''
                    ? trim((string)$row[$termIndex])
                    : $defaultTerm;
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
                }

                $status = in_array($term, ['2430', '2330', '2230']) ? 'expired' : 'active';

                // Find existing record for auto-update
                $existing = null;

                // 1. Try matching by student_id and term first
                if (!empty($studentId)) {
                    $existing = ClubMember::where('student_id', $studentId)
                        ->where('term', $term)
                        ->first();

                    // If not found, match by student_id across any term
                    if (!$existing) {
                        $existing = ClubMember::where('student_id', $studentId)->first();
                    }
                }

                // 2. If still not found, match by name and term
                if (!$existing && !empty($name)) {
                    $existing = ClubMember::where('name', $name)
                        ->where('term', $term)
                        ->first();
                }

                if ($existing) {
                    $existing->update([
                        'name' => $name,
                        'student_id' => !empty($studentId) ? $studentId : $existing->student_id,
                        'term' => $term,
                        'email' => !empty($email) ? $email : $existing->email,
                        'status' => $status,
                        'updated_at' => $now,
                    ]);
                    $updatedCount++;
                } else {
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

            $totalProcessed = $createdCount + $updatedCount;
            return redirect()->route('admin.club_members.index')->with(
                'message',
                "Excel sync successful! {$createdCount} new members added, {$updatedCount} records auto-updated ({$totalProcessed} total synced)."
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

        // Ordering
        $query->orderBy('term', 'desc')->orderBy('name', 'asc');

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

        // Headers
        $sheet->setCellValue('A1', 'Student ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Registration Term');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Date Added');

        // Style Header Row
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $rowNum = 2;
        foreach ($members as $m) {
            $sheet->setCellValue('A' . $rowNum, $m->student_id ?? '');
            $sheet->setCellValue('B' . $rowNum, $m->name);
            $sheet->setCellValue('C' . $rowNum, $m->term);
            $sheet->setCellValue('D' . $rowNum, $m->email ?? '');
            $sheet->setCellValue('E' . $rowNum, ucfirst($m->status));
            $sheet->setCellValue('F' . $rowNum, $m->created_at ? $m->created_at->format('Y-m-d') : '');
            $rowNum++;
        }

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
     * Download a blank Excel import template.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template');

        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Student ID');
        $sheet->setCellValue('C1', 'Term');
        $sheet->setCellValue('D1', 'Email');

        // Sample Row
        $sheet->setCellValue('A2', 'Muhammad Raziq');
        $sheet->setCellValue('B2', '1211103984');
        $sheet->setCellValue('C2', '2530');
        $sheet->setCellValue('D2', 'raziq@student.mmu.edu.my');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'GDGoC_Members_Import_Template.xlsx';

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}

