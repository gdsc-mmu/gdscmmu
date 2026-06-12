<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    //

    public function index() : View{

        $allNews = \App\Models\News::all();

        return view('news', ['allNews' => $allNews]);
    }

    public function show($id) : View{

        $newsbyId = \App\Models\News::find($id);

        if (!$newsbyId) {
            abort(404, 'News item not found');
        }

        return view('news_specific', ['newsbyId' => $newsbyId]);
    }
}
