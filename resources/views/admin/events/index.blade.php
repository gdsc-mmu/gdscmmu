<x-layout>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="admin-page-wrapper">
        <div class="admin-container">

            <!-- Sub-Navigation Bar -->
            @include('admin.partials.nav', ['active' => 'events'])

            <!-- Header Card -->
            <div class="admin-header-card" data-aos="fade-down" data-aos-duration="800">
                <div class="admin-header-stripe">
                    <div class="stripe-blue"></div>
                    <div class="stripe-red"></div>
                    <div class="stripe-yellow"></div>
                    <div class="stripe-green"></div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <div class="admin-badge">
                            <i class="fa fa-calendar text-primary"></i>
                            <span>Event Management</span>
                        </div>
                        <h1 class="admin-title">Manage Events</h1>
                        <p class="admin-subtitle">Schedule, organize, edit, and maintain GDGoC workshops, hackathons, and speaker sessions.</p>
                    </div>
                    <div class="admin-header-actions mt-3 mt-md-0">
                        <a href="/events/create" class="btn-admin-action btn-admin-primary">
                            <i class="fa fa-plus-circle"></i>
                            <span>Create New Event</span>
                        </a>
                        <a href="/events" target="_blank" class="btn-admin-action btn-admin-secondary">
                            <i class="fa fa-external-link"></i>
                            <span>View Public Events</span>
                        </a>
                    </div>
                </div>
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stats Overview Strip -->
            <div class="stats-grid mb-4" data-aos="fade-up" data-aos-duration="800">
                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-blue">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $eventsCount ?? 0 }}</div>
                        <p class="stat-label">Total Events</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-green">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $upcomingCount ?? 0 }}</div>
                        <p class="stat-label">Upcoming / Live</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-yellow">
                        <i class="fa fa-history"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $pastCount ?? 0 }}</div>
                        <p class="stat-label">Past Events</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-red">
                        <i class="fa fa-plus-square"></i>
                    </div>
                    <div>
                        <a href="/events/create" class="text-decoration-none fw-bold" style="color: #0e174a; font-size: 1.1rem;">
                            + New Event
                        </a>
                        <p class="stat-label">Quick Publish</p>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="team-filter-bar mb-4" data-aos="fade-up" data-aos-duration="850">
                <div class="team-search-box flex-grow-1">
                    <i class="fa fa-search"></i>
                    <input type="text" id="adminEventSearch" class="team-search-input" placeholder="Search events by title or description...">
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 event-filter-pill active" data-filter="all">
                        All ({{ $eventsCount ?? 0 }})
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 event-filter-pill" data-filter="upcoming">
                        Upcoming ({{ $upcomingCount ?? 0 }})
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 event-filter-pill" data-filter="past">
                        Past ({{ $pastCount ?? 0 }})
                    </button>
                </div>
            </div>

            <!-- Events Grid -->
            <div class="row g-4" id="adminEventsGrid" data-aos="fade-up" data-aos-duration="900">
                @forelse($events as $eventItem)
                    @php
                        $isUpcoming = false;
                        $isPast = false;
                        $isToday = false;
                        try {
                            $dateObj = \Carbon\Carbon::parse($eventItem->date);
                            $isUpcoming = $dateObj->isFuture();
                            $isPast = $dateObj->isPast() && !$dateObj->isToday();
                            $isToday = $dateObj->isToday();
                        } catch (\Exception $e) {
                            $isPast = true;
                        }
                        $statusClass = $isToday ? 'event-status-live' : ($isUpcoming ? 'event-status-upcoming' : 'event-status-past');
                        $statusLabel = $isToday ? 'Live Today' : ($isUpcoming ? 'Upcoming' : 'Past');
                        $filterTag = $isToday || $isUpcoming ? 'upcoming' : 'past';
                    @endphp

                    <div class="col-lg-4 col-md-6 event-admin-item" 
                         data-filter="{{ $filterTag }}" 
                         data-title="{{ strtolower($eventItem->title) }}" 
                         data-desc="{{ strtolower($eventItem->description ?? '') }}">
                        <div class="event-admin-card">
                            <div class="event-admin-img-wrap">
                                <img src="{{ $eventItem->image ? asset('images/' . $eventItem->image) : 'https://images.unsplash.com/photo-1520004434532-668416a08753?q=80&w=800&auto=format&fit=crop' }}" 
                                     alt="{{ $eventItem->title }}" 
                                     class="event-admin-img">
                                <span class="event-admin-status {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="event-admin-body">
                                <div class="event-admin-date-time">
                                    <span><i class="fa fa-calendar me-1 text-primary"></i> {{ $eventItem->date }}</span>
                                    @if(!empty($eventItem->time))
                                        <span>&bull;</span>
                                        <span><i class="fa fa-clock-o me-1 text-muted"></i> {{ $eventItem->time }}</span>
                                    @endif
                                </div>

                                <h3 class="event-admin-title">
                                    {{ $eventItem->title }}
                                </h3>

                                <p class="event-admin-desc">
                                    {{ \Illuminate\Support\Str::limit($eventItem->description, 130) }}
                                </p>

                                <div class="event-admin-footer">
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="/events/{{ $eventItem->id }}/edit" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Edit Event">
                                            <i class="fa fa-pencil me-1"></i> Edit
                                        </a>
                                        <a href="/events/{{ $eventItem->id }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="View Public Page">
                                            <i class="fa fa-eye me-1"></i> View
                                        </a>
                                    </div>

                                    <form action="/events/{{ $eventItem->id }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete event \'{{ addslashes($eventItem->title) }}\'?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;" title="Delete Event">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 border shadow-sm">
                            <i class="fa fa-calendar-o text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-secondary">No Events Found</h4>
                            <p class="text-muted">You haven't scheduled any GDGoC events yet.</p>
                            <a href="/events/create" class="btn btn-primary rounded-pill px-4 mt-2">
                                <i class="fa fa-plus me-1"></i> Create Your First Event
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Empty Search State -->
            <div id="noEventsMatchMsg" class="d-none text-center py-5">
                <div class="p-5 bg-white rounded-4 border shadow-sm">
                    <i class="fa fa-search text-muted" style="font-size: 2.5rem;"></i>
                    <h5 class="mt-3 text-secondary">No matching events found</h5>
                    <p class="text-muted">Try adjusting your search terms or filter selection.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Client-side Search and Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('adminEventSearch');
            const filterPills = document.querySelectorAll('.event-filter-pill');
            const eventItems = document.querySelectorAll('.event-admin-item');
            const noMatchMsg = document.getElementById('noEventsMatchMsg');

            let currentFilter = 'all';

            function filterEvents() {
                const query = (searchInput.value || '').trim().toLowerCase();
                let visibleCount = 0;

                eventItems.forEach(item => {
                    const title = item.getAttribute('data-title') || '';
                    const desc = item.getAttribute('data-desc') || '';
                    const filterTag = item.getAttribute('data-filter') || '';

                    const matchesQuery = query === '' || title.includes(query) || desc.includes(query);
                    const matchesFilter = currentFilter === 'all' || filterTag === currentFilter;

                    if (matchesQuery && matchesFilter) {
                        item.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if (noMatchMsg) {
                    if (visibleCount === 0 && eventItems.length > 0) {
                        noMatchMsg.classList.remove('d-none');
                    } else {
                        noMatchMsg.classList.add('d-none');
                    }
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterEvents);
            }

            filterPills.forEach(pill => {
                pill.addEventListener('click', function () {
                    filterPills.forEach(p => {
                        p.classList.remove('btn-primary', 'active');
                        p.classList.add('btn-outline-secondary');
                    });
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-primary', 'active');

                    currentFilter = this.getAttribute('data-filter') || 'all';
                    filterEvents();
                });
            });
        });
    </script>
</x-layout>

