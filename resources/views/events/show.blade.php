<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <h1>Show page</h1>
        <p>{{ $event->name }}</p>
        <p>{{ $event->description }}</p>
        <p>{{ $event->date }}</p>
        <p>{{ $event->time }}</p>
        <p>Belongs to user id : {{ $event->user_id }}</p>
    </div>
    </div>
</x-layout>
