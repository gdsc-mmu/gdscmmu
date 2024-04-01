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

    public function editPage(){
      $event = Event::orderBy('created_at', 'desc')->get();
      return view('events.edit', ['event' => $event]); 
  }
    
    public function store(Request $request) {

    $request->validate([
      'name' => 'required',
      'description' => 'required',
      'date' => 'required|date',
    //'time' => 'required|date_format: H:i', 
      'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
  
    $event = new Event;
  
    $file_name = time() . '.' . request()->image->getClientOriginalExtension();
    request()->image->move(public_path('images'), $file_name);
    
    $event->name = $request->name;
    $event->description = $request->description;
    $event->date = $request->date;
  //$event->time = $request->time;
    if ($request->hasFile('image')) {
      $event->image = $file_name;
    }
    $event->save();

    return redirect()->route('events.index')->with('success', 'Event created successfully.');
  }

    // Show page
    public function show(Event $event) {
        return view('events.show', ['event' => $event]);
    }

    public function destroy($id){

      $event = Event::find($id);
      $event->delete();
      return redirect('/events')->with('message', 'Event deleted successfully');
    }


}
