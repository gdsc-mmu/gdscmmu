<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiveController extends Controller
{
    public function dives()
    {
        return view('dives.index');
    }
}
