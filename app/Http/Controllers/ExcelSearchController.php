<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClubMember;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelSearchController extends Controller
{
    // Display the form
    public function showForm()
    {
        return view('index');
    }

    public function search(Request $request)
    {
        $name = trim((string)$request->input('name'));

        if (empty($name)) {
            return redirect('/#membership')->with('error', 'Please enter a name or student ID to check membership.');
        }

        // 1. Query the database first if members exist
        if (ClubMember::count() > 0) {
            // Only active members are displayed in the member tracker search (archived/inactive terms are hidden)
            $dbMembers = ClubMember::where('status', 'active')
                ->where(function($q) use ($name) {
                    $q->where('name', 'LIKE', "%{$name}%")
                      ->orWhere('student_id', 'LIKE', "%{$name}%");
                })->get();

            if ($dbMembers->isNotEmpty()) {
                $headers = ['student id', 'name', 'registration terms'];
                $nameIndex = 1;
                $results = [];

                foreach ($dbMembers as $m) {
                    $results[] = [
                        0 => $m->student_id ?? 'N/A',
                        1 => $m->name,
                        2 => $m->term,
                    ];
                }

                $studentTerm = $results[0][2] ?? null;
                $expired_terms = ['2430', '2330', '2230'];

                if (in_array($studentTerm, $expired_terms)) {
                    return redirect('/#membership')->with('error', 'Your membership has expired. Please renew your membership with us!');
                }

                return redirect('/#membership')->with(compact('results', 'name', 'nameIndex', 'headers'));
            }

            // If members exist in DB but no active match was found (either not registered, or their term has been archived/set inactive)
            return redirect('/#membership')->with(compact('name'))->with('results', []);
        }

        // 2. Fallback to static Excel file if no DB matches found
        $path = public_path('assets/2430_2530_Members.xlsx');
        if (!file_exists($path)) {
            return redirect('/#membership')->with(compact('name'))->with('results', []);
        }

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (empty($rows)) {
                return back()->with('error', 'Excel file is empty or cannot be read!');
            }

            $headers = $rows[0];
            $headers = array_map('strtolower', $headers);
            $dataRows = array_slice($rows, 1);
            $nameIndex = array_search('name', $headers);

            if ($nameIndex === false) {
                return back()->with('error', 'Name column cannot be found in the database.');
            }

            $results = array_values(array_filter($dataRows, function($row) use ($nameIndex, $name) {
                return isset($row[$nameIndex]) && stripos($row[$nameIndex], $name) !== false;
            }));

            $termIndex = array_search('registration terms', $headers);
            if ($termIndex === false) {
                $termIndex = array_search('term', $headers);
            }

            $studentTerm = $results[0][$termIndex] ?? null;
            $expired_terms = ['2430', '2330', '2230'];

            if (in_array($studentTerm, $expired_terms)) {
                return redirect('/#membership')->with('error', 'Your membership has expired. Please renew your membership with us!');
            }

            return redirect('/#membership')->with(compact('results', 'name', 'nameIndex', 'headers'));
        } catch (\Throwable $e) {
            return redirect('/#membership')->with(compact('name'))->with('results', []);
        }
    }
}
