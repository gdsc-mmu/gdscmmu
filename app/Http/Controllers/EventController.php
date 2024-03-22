<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event; 


class EventController extends Controller
{
    // Events page
    function events() {
        $event = Event::orderBy('created_at', 'desc')->get();
        return view('events.index', ['event' => $event]); 
    }

    public function create(){
       
        return view('events.create'); 
    }
    
    public function store(Request $request) {

    $request->validate([
      'title' => 'required',
      'description' => 'required',
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
  
    $event = new Event;
  
    $file_name = time() . '.' . request()->image->getClientOriginalExtension();
    request()->image->move(public_path('images'), $file_name);
  
    $event->title = $request->title;
    $event->description = $request->description;
    $event->image = $file_name;
    $event->save();

    return redirect()->route('events.index')->with('success', 'Event created successfully.');
  }
}
