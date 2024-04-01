<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <h1>Show page</h1>
        <p>{{ $event->name }}</p>
        <p>{{ $event->description }}</p>
        <p>{{ $event->date }}</p>
        <p>{{ $event->time }}</p>
        <p>Belongs to user id : {{ $event->user_id }}</p>

        @if (Auth::check())
            <div class="d-flex">
                <a class="btn btn-warning">
                    Edit event
                </a>
                <a href="/events/edit-page/delete/{{$event->id}}" class="btn btn-danger ms-2">
                    Delete event
                </a>
            </div>
        @endif
    </div>
</x-layout>
