<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <h1>Show page</h1>

        <div class="card mb-4" style="max-width: 600px;">
            <img src="{{ asset('images/' . $event->image) }}" class="card-img-top img-fluid rounded workshop-graphic" alt="Event poster">
            <div class="card-body">
    
                <h2 class="card-title">{{ $event->title }}</h2>
                <p class="card-text">Description: {{ $event->description }}</p>
                <p class="card-text">Date Created: {{ $event->date }}, {{ $event->time }}</p>
                <p class="card-text">Belongs to user id: {{ $event->user_id }}</p>

                @if (Auth::check() && Auth::user()->id == $event->user_id)
                    <div class="d-flex">
                        <a href="/events/{{$event->id}}/edit" class="btn btn-warning btn-sm me-2">Edit event</a>
                        <form href="/events/{{$event->id}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger btn-sm">Delete event</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
