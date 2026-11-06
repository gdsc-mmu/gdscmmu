<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event; 


class EventController extends Controller
{
    /**
     * Dedicated Admin Events Management View
     */
    public function adminIndex()
    {
        $events = Event::orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
        $eventsCount = $events->count();
        $upcomingCount = $events->filter(function($e) {
            try {
                return \Carbon\Carbon::parse($e->date)->isFuture() || \Carbon\Carbon::parse($e->date)->isToday();
            } catch (\Exception $ex) {
                return false;
            }
        })->count();
        $pastCount = $eventsCount - $upcomingCount;

        return view('admin.events.index', compact('events', 'eventsCount', 'upcomingCount', 'pastCount'));
    }

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
      /*if (auth()->user()->id !== $event->user_id) {
          return redirect('/events')->with('error', 'Unauthorized access');
      }*/
      return view('events.edit', ['event' => $event]); 
  }
    
    public function store(Request $request) {

    $formFields = $request->validate([
      'title' => 'required',
      'description' => 'required',
      'date' => 'required|date',
      'time' => 'required',
      'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    //$formFields['date'] = now()->format('Y-m-d');
    //$formFields['time'] = now()->format('H:i:s');
    $formFields['user_id'] = auth()->user()->id;
  
    if ($request->hasFile('image')) {
        $file_name = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images'), $file_name);
        $formFields['image'] = $file_name;
    }
    Event::create($formFields);

    return redirect()->route('events.index')->with('message', 'Event created successfully.');
  }

    // Show page
    public function show(Event $event) {
        return view('events.show', ['event' => $event]);
    }

    public function destroy(Request $request, $id){
      $event = Event::find($id);
      /*if (auth()->user()->id !== $event->user_id) {
          return redirect('/events')->with('error', 'Unauthorized access');
      }*/

      if ($event) {
          $event->delete();
      }

      if ($request->wantsJson() || $request->ajax()) {
          return response()->json(['success' => true, 'message' => 'Event deleted successfully']);
      }

      return redirect()->back()->with('message', 'Event deleted successfully');
    }

    public function update(Request $request, Event $event) {
        $formFields = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file_name = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $file_name);
            $formFields['image'] = $file_name;
        }

        //$formFields['date'] = now()->format('Y-m-d');
        //$formFields['time'] = now()->format('H:i:s');
        $event->update($formFields);

        return redirect('/events')->with('message', 'Event updated successfully');
    }


}
