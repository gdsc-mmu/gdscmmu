<x-layout>
    <link rel="stylesheet" href="{{asset('css/events.css')}}">
    <div class="row p-5">
        <div class="col-md-8 offset-md-2 mt-5">
            <!-- Event Banner Section -->
            <div data-aos="fade-down" data-aos-duration="1500" class="mt-2 mb-4 text-center banner-section">
                <img src="{{$event->image ? asset('images/' . $event->image) : 'https://images.unsplash.com/photo-1520004434532-668416a08753?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'}}"
                    class="img-fluid mx-auto d-block rounded-start rounded-end shadow"
                    style="max-width: auto; height: auto;" alt="Event banner">
            </div>

            <!-- Event Title Section -->
            <div data-aos="fade-down" data-aos-duration="1500" class="text-center mb-4">
                <h1 style="color: white;">{{ $event->title }}</h1>
            </div>

            <!-- About the Event Section -->
            <section data-aos="fade-down" data-aos-duration="1500" class="bg-light p-4 rounded mb-4 shadow">
                <div class="text-center">
                    <h2 class="mb-4">About the Event</h2>
                    <hr style="border-top: 2px solid black; margin: 20px 0;">
                    <p class="card-text" style="font-size: 1.1rem;">{!!nl2br($event->description)!!}</p>
                </div>
            </section>

            <!-- Card Footer (Actions) -->
            <div class="card-footer text-center mb-3">
                @if (Auth::check() && Auth::user()->id == $event->user_id)
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
