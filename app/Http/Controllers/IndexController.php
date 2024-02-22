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
        return view('team');
    }

    // Events page
    function events() {
        return view('events');
    }
}
