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

    <div id="events" class="container my-5">
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="event-filters text-center">
                    <button class="filter-btn active" data-filter="all">All Events</button>
                    <button class="filter-btn" data-filter="upcoming">Upcoming</button>
                    <button class="filter-btn" data-filter="past">Past Events</button>
                    <button class="filter-btn" data-filter="workshop">Workshops</button>
                </div>
            </div>
        </div>

        <div class="row" id="events-container">
            @foreach ($event as $eventItem) 
                @php
                    $eventDate = \Carbon\Carbon::parse($eventItem->date);
                    $isUpcoming = $eventDate->isFuture();
                    $isPast = $eventDate->isPast();
                    $isToday = $eventDate->isToday();
                    $isWorkshop = str_contains(strtolower($eventItem->event_type ?? ''), 'workshop') || 
                                  str_contains(strtolower($eventItem->title), 'workshop');
                @endphp
                
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4 event-card-wrapper" 
                     data-aos="fade-up" 
                     data-aos-duration="1000"
                     data-filter-tags="{{ $isUpcoming ? 'upcoming' : 'past' }} {{ $isWorkshop ? 'workshop' : 'event' }}">
                    <div class="event-card h-100">
                        <!-- Event Image -->
                        <div class="event-image-container">
                            <img src="{{$eventItem->image ? asset('images/' . $eventItem->image) : 'https://images.unsplash.com/photo-1520004434532-668416a08753?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }}" 
                                 class="event-image" 
                                 alt="{{ $eventItem->title }}">
                            
                            <!-- Status Badge -->
                            @if($isToday)
                                <span class="status-badge live-badge">
                                    <i class="fa fa-circle"></i> Live
                                </span>
                            @elseif($isUpcoming)
                                <span class="status-badge upcoming-badge">
                                    <i class="fa fa-calendar"></i> Upcoming
                                </span>
                            @else
                                <span class="status-badge past-badge">
                                    <i class="fa fa-check"></i> Completed
                                </span>
                            @endif

                            <!-- Registration Count (if available) -->
                            @if($eventItem->max_participants && $isUpcoming)
                                <div class="registration-count">
                                    <i class="fa fa-users"></i>
                                    <span>{{ $eventItem->registrations_count ?? 0 }}/{{ $eventItem->max_participants }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Event Content -->
                        <div class="event-content">
                            <!-- Event Date and Time -->
                            <div class="event-datetime">
                                <div class="date-info">
                                    <i class="fa fa-calendar-o"></i>
                                    <span>{{ $eventDate->format('M d, Y') }}</span>
                                </div>
                                @if($eventItem->start_time)
                                <div class="time-info">
                                    <i class="fa fa-clock-o"></i>
                                    <span>{{ \Carbon\Carbon::parse($eventItem->start_time)->format('g:i A') }}</span>
                                    @if($eventItem->end_time)
                                        - {{ \Carbon\Carbon::parse($eventItem->end_time)->format('g:i A') }}
                                    @endif
                                </div>
                                @endif
                            </div>

                            <!-- Location (if available) -->
                            @if($eventItem->location)
                            <div class="event-location">
                                <i class="fa fa-map-marker"></i>
                                <span>{{ $eventItem->location }}</span>
                            </div>
                            @endif

                            <!-- Event Title -->
                            <h4 class="event-title">{{ $eventItem->title }}</h4>

                            <!-- Event Description -->
                            <p class="event-description">
                                {{ Str::limit(strip_tags($eventItem->description), 120, '...') }}
                            </p>

                            <!-- Event Type and Category -->
                            <div class="event-meta">
                                @if($eventItem->event_type)
                                <span class="type-badge">
                                    @if(str_contains(strtolower($eventItem->event_type), 'workshop'))
                                        <i class="fa fa-wrench"></i> {{ $eventItem->event_type }}
                                    @elseif(str_contains(strtolower($eventItem->event_type), 'seminar'))
                                        <i class="fa fa-graduation-cap"></i> {{ $eventItem->event_type }}
                                    @elseif(str_contains(strtolower($eventItem->event_type), 'competition'))
                                        <i class="fa fa-trophy"></i> {{ $eventItem->event_type }}
                                    @else
                                        <i class="fa fa-calendar"></i> {{ $eventItem->event_type }}
                                    @endif
                                </span>
                                @endif

                                @if($eventItem->category)
                                <span class="category-badge">
                                    {{ $eventItem->category }}
                                </span>
                                @endif
                            </div>

                            <!-- Event Actions -->
                            <div class="event-actions">
                                <div class="action-buttons">
                                    <a href="/events/{{$eventItem->id}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-eye"></i> View Details
                                    </a>
                                </div>

                                <!-- Registration Status -->
                                @if($isUpcoming)
                                    <div class="registration-status mt-2">
                                        @if($eventItem->registration_required)
                                            <small class="text-success">
                                                <i class="fa fa-check-circle"></i> Registration Required
                                            </small>
                                        @else
                                            <small class="text-info">
                                                <i class="fa fa-users"></i> Open Event
                                            </small>
                                        @endif
                                    </div>
                                @endif

                                <!-- Event Organizer -->
                                @if($eventItem->organizer)
                                    <div class="event-organizer mt-2">
                                        <small class="text-muted">
                                            <i class="fa fa-user"></i> Organized by {{ $eventItem->organizer }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($event->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-calendar-times-o fa-5x text-muted mb-3"></i>
                <h3 class="text-muted">No Events Found</h3>
                <p class="text-muted">Check back later for upcoming events!</p>
            </div>
        @endif
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

        if (form) {
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
        }

        // Event Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const eventCards = document.querySelectorAll('.event-card-wrapper');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');

                    eventCards.forEach(card => {
                        const filterTags = card.getAttribute('data-filter-tags');
                        
                        if (filter === 'all' || filterTags.includes(filter)) {
                            card.style.display = 'block';
                            card.style.animation = 'fadeIn 0.5s ease-in';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
    <script>
        AOS.init();
    </script>
</x-layout>
