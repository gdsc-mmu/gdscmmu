<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelSearchController extends Controller
{
    // Display the form
    public function showForm()
    {
        return view(view: 'index');
    }

    public function search(Request $request)
    {
        $name = $request->input('name');

        
        $path = public_path('assets/2430_2530_Members.xlsx');
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if(empty($rows)){
            return back() -> with ('error' , 'excel file is empty or cannot be read!');
        }

        $headers = $rows[0];
        $headers = array_map('strtolower', $headers);
        $dataRows = array_slice($rows, 1);
        $nameIndex = array_search('name' , $headers);

        if($nameIndex === false){
             return back()->with('error', 'Name column cannot be found in the database.');
        }

        $results = array_values(array_filter($dataRows , function($row) use ($nameIndex , $name){
             return isset($row[$nameIndex]) && stripos($row[$nameIndex] , $name) !== false;
        }));


       

        // Get first sheet
       

        return redirect('/#membership')->with(compact('results', 'name' , 'nameIndex' , 'headers'));
    }
}
