<?php

namespace App\Http\Controllers;

use App\Models\Dive;
use Illuminate\Http\Request;

class DiveController extends Controller
{
    public function dives()
    {
        $dives = Dive::orderBy('created_at', 'desc')->get();
        return view('dives.index', ['dives' => $dives]);
    }
}
