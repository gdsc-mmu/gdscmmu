<x-layout>
    <link rel="stylesheet" href="{{asset('css/events.css')}}">
    <link rel="stylesheet" href="{{asset('css/calendar.css')}}">

    <div id="events-header" class="main container-fluid d-flex align-items-center text-center">
        <div data-aos="fade-down" data-aos-duration="1500" class="container">
            <h1>Events</h1>
            <h3>All the Stuff We Do</h3>
            <div class="buttons-container">
                <a href="#events" class="btn find-out-more mt-4">View Events</a>
            </div>
            @if (Auth::check() && Auth::user()->user_type == 'committee')
                <div class="buttons-container">
                    <a href="/events/create" class="btn find-out-more mt-4">Create New Event</a>
                </div>
            @endif
        </div>
    </div>


    <div id="events">
        @foreach ($event as $event) 
            @if ($loop->iteration % 2 == 0)
                <x-event class="event-even">
                    <div class="col-md-6 mt-4 mb-4 d-flex flex-column justify-content-center">
                        <h3 class="mb-4">{{ $event->title }}</h3>
                        <p>{!! nl2br($event->description) !!}</p>
                        <a href="/events/{{$event->id}}">
                            <button class="btn btn-warning">
                                Read More!
                            </button>
                        </a>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{$event->image ? asset('images/' . $event->image) : 'https://images.unsplash.com/photo-1520004434532-668416a08753?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }}" class="img-fluid rounded workshop-graphic"
                                                                          alt="Webdev poster">
                    </div>
                </x-event>
            @else
                <x-event class="event-odd">
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <img src="{{$event->image ? asset('images/' . $event->image) : 'https://images.unsplash.com/photo-1520004434532-668416a08753?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }}" class="img-fluid rounded workshop-graphic"
                                                                          alt="Webdev poster">
                    </div>
                    <div class="col-md-6 mt-4 mb-4 d-flex flex-column justify-content-center">
                        <h3 class="mb-4">{{ $event->title }}</h3>
                        <p>{!! nl2br($event->description) !!}</p>
                        <a href="/events/{{$event->id}}">
                            <button class="btn btn-warning">
                                Read More!
                            </button>
                        </a>
                    </div>
                </x-event>
            @endif

        @endforeach
    </div>

    {{--<div class="event event-even container-fluid align-items-center text-center">--}}
        {{--<div data-aos="fade-down" data-aos-duration="1500" class="container">--}}
            {{--<h3 class="mb-4">Future Events</h2>--}}
                {{--<div id="calendar"></div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <script>
        const scriptURL = 'https://script.google.com/macros/s/AKfycbwnUWrY8gzGqbVZ4on6-hsPAbms8khQltLrV85cqOmO6kQklv0/exec'
        const form = document.forms['submit-to-google-sheet']

        form.addEventListener('submit', e => {
            e.preventDefault()
            fetch(scriptURL, {
                method: 'POST',
                body: new FormData(form)
            })
                .then(response => {
                    let successbox = document.querySelector('.success-message');
                    successbox.innerHTML = '<div class="alert alert-primary">We will get back to you ASAP!</div>';
                    form.name = form.email = form.message = ' ';
                    console.log('Success!', response)
                })
                .catch(error => console.error('Error!', error.message))
        })
    </script>
    <script>
        AOS.init();
    </script>
</x-layout>
