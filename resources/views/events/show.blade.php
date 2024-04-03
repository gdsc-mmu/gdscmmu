<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <h1>Show page</h1>
        <p>{{ $event->title }}</p>
        <p>{{ $event->description }}</p>
        <p>{{ $event->date }}</p>
        <p>{{ $event->time }}</p>
        <p>Belongs to user id : {{ $event->user_id }}</p>

        @if (Auth::check() && Auth::user()->id == $event->user_id)
            <div class="d-flex">
                <a href="/events/{{$event->id}}/edit" class="btn btn-warning">
                    Edit event
                </a>
                <form href="/events/{{$event->id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger ms-2">Delete event</button>
                </form>
            </div>
        @endif
    </div>
</x-layout>
