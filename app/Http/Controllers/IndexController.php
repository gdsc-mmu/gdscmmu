<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    // Home page
    function index() {
        return view('index');
    }

    // Team page
    function team() {
        $divisions = \App\Models\TeamDivision::with('members')->orderBy('order')->orderBy('id')->get();
        return view('team', compact('divisions'));
    }

    function membership(){
        return view ('membership');
    }

    
    function news(){
        return view('news');
    }

    function newsDetails(){
        return view('news_specific');
    }
}
