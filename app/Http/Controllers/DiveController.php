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

    public function show(Dive $dive)
    {
        return view('dives.show', ['dive' => $dive]);
    }

    public function edit(Dive $dive)
    {
        return view('dives.edit', ['dive' => $dive]);
    }

}
