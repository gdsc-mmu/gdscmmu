<x-layout>
    <link rel="stylesheet" href="{{asset('css/events.css')}}">
    <div class="row p-4">
        <div class="col-md-8 offset-md-2 mt-5">
            <!-- Event Banner Section -->
            <div data-aos="fade-down" data-aos-duration="1500" class="mt-2 mb-4 text-center banner-section">
                <img src="{{ $event->image_url }}"
                    class="img-fluid mx-auto d-block rounded shadow"
                    style="max-height: 400px; width: 100%; object-fit: cover;" alt="{{ $event->title }}">
            </div>

            <!-- Event Title Section -->
            <div data-aos="fade-down" data-aos-duration="1500" class="text-center mb-4">
                <h1 class="text-white fs-1">{{ $event->title }}</h1>
                @if($event->bevy_url)
                    <div class="mt-3">
                        <a href="{{ $event->bevy_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
                            <i class="fa fa-ticket me-2"></i> RSVP on Official Bevy Platform
                        </a>
                    </div>
                @endif
            </div>

            <!-- About the Event Section -->
            <section data-aos="fade-down" data-aos-duration="1500" class="bg-light p-4 rounded mb-4 shadow">
                <div class="text-center text-md-start">
                    <h2 class="mb-4 fs-2 text-center">About the Event</h2>
                    <hr style="border-top: 2px solid black; margin: 20px 0;">
                    <div class="card-text text-start" style="font-size: 1.1rem; line-height: 1.7;">
                        {!! str_contains($event->description, '<p>') ? $event->description : nl2br(e($event->description)) !!}
                    </div>
                </div>
            </section>

            <!-- Card Footer (Actions) -->
            <div class="card-footer text-center mb-3">
                @if (Auth::check() && Auth::user()->user_type == 'committee')
                <div class="d-flex justify-content-center">
                    <div class="me-2">
                        <a href="/events/{{$event->id}}/edit" class="btn btn-warning">Edit event</a>
                    </div>
                    <div>
                        <form action="/events/{{$event->id}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete event</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
