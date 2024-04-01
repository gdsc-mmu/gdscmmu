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

    public function edit($id){
      $event = Event::find($id);
      return view('events.edit', ['event' => $event]); 
  }
    
    public function store(Request $request) {

    $formFields = $request->validate([
      'title' => 'required',
      'description' => 'required',
      'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $formFields['date'] = now()->format('Y-m-d');
    $formFields['time'] = now()->format('H:i:s');
    $formFields['user_id'] = auth()->user()->id;
  
    if ($request->hasFile('image')) {
        $file_name = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images'), $file_name);
        $formFields['image'] = $file_name;
    }
    Event::create($formFields);

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

    public function update(Request $request, Event $event) {
        $formFields = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file_name = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $file_name);
            $formFields['image'] = $file_name;
        }

        $formFields['date'] = now()->format('Y-m-d');
        $formFields['time'] = now()->format('H:i:s');
        $event->update($formFields);

        return redirect('/events')->with('message', 'Event updated successfully');
    }


}
