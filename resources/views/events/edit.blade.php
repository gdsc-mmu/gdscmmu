<x-layout>
    <h1>Edit page</h1>
</x-layout>
<div>
    <div id="events">
    @foreach ($event as $event) 

<x-event class="event-odd">
    <div class="col-md-6 d-flex justify-content-center align-items-center">
    <img src="{{ asset('images/' . $event->image) }}" class="img-fluid rounded workshop-graphic"
            alt="Webdev poster">
    </div>
    <div class="col-md-6 mt-4 mb-4 d-flex flex-column justify-content-center">
        <h3 class="mb-4">{{ $event->title }}</h3>
        <p>{{ $event->description }}</p>
        <a >
            <button class="btn btn-warning">
                Edit event
            </button>
        </a>
        <a href="/events/edit-page/delete/{{$event->id}}">
            <button class="btn btn-warning">
                Delete event
            </button>
        </a>
    </div>
</x-event>
@endforeach
</div>
