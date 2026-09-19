<x-layout>
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">

    <div class="admin-page-wrapper">
        <div class="admin-container">

            <!-- Welcome Header Card -->
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
                            <i class="fa fa-shield"></i>
                            <span>Admin Control Center</span>
                        </div>
                        <h1 class="admin-title">Welcome back, {{ Auth::user()->name }} 👋</h1>
                        <p class="admin-subtitle">Manage your GDGoC MMU content, events, news feeds, and team structure.</p>
                    </div>
                    <div class="admin-header-actions mt-3 mt-md-0">
                        <a href="/" target="_blank" class="btn-admin-action btn-admin-secondary">
                            <i class="fa fa-external-link"></i>
                            <span>View Live Site</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Overview Strip -->
            <div class="stats-grid" data-aos="fade-up" data-aos-duration="800">
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
                    <div class="stat-icon-wrapper icon-red">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                    <div>
                        <div class="stat-number">{{ $newsCount ?? 0 }}</div>
                        <p class="stat-label">Articles Published</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-yellow">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-number">Core</div>
                        <p class="stat-label">Committee Leads</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrapper icon-green">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-number">Active</div>
                        <p class="stat-label">Portal Status</p>
                    </div>
                </div>
            </div>

            <!-- Main Management Cards Grid -->
            <div class="management-grid" data-aos="fade-up" data-aos-duration="900">

                <!-- Card 1: Manage Events -->
                <div class="mgmt-card">
                    <div class="mgmt-card-stripe stripe-accent-blue"></div>
                    <div class="mgmt-card-body">
                        <div class="mgmt-card-header">
                            <div class="mgmt-card-icon icon-blue">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div>
                                <h3 class="mgmt-card-title">Manage Events</h3>
                                <span class="mgmt-card-tag text-primary">{{ $eventsCount ?? 0 }} Total Scheduled</span>
                            </div>
                        </div>
                        <p class="mgmt-card-desc">
                            Organize, schedule, and update GDGoC workshops, hackathons, speaker sessions, and tech talks.
                        </p>
                        <div class="mgmt-card-actions">
                            <a href="/events/create" class="btn-card-action btn-card-accent">
                                <span><i class="fa fa-plus-circle mr-2"></i>Create New Event</span>
                                <i class="fa fa-arrow-right"></i>
                            </a>
                            <a href="/events" class="btn-card-action btn-card-primary">
                                <span><i class="fa fa-list mr-2"></i>Browse All Events</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Manage News Feed -->
                <div class="mgmt-card">
                    <div class="mgmt-card-stripe stripe-accent-red"></div>
                    <div class="mgmt-card-body">
                        <div class="mgmt-card-header">
                            <div class="mgmt-card-icon icon-red">
                                <i class="fa fa-newspaper-o"></i>
                            </div>
                            <div>
                                <h3 class="mgmt-card-title">Manage News Feed</h3>
                                <span class="mgmt-card-tag text-danger">{{ $newsCount ?? 0 }} Stories Live</span>
                            </div>
                        </div>
                        <p class="mgmt-card-desc">
                            Publish tech updates, AI breakthroughs, cybersecurity trends, and software engineering insights for the MMU community.
                        </p>
                        <div class="mgmt-card-actions">
                            <a href="/news" class="btn-card-action btn-card-primary">
                                <span><i class="fa fa-rss mr-2"></i>View News Feed</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Manage Team Members -->
                <div class="mgmt-card">
                    <div class="mgmt-card-stripe stripe-accent-green"></div>
                    <div class="mgmt-card-body">
                        <div class="mgmt-card-header">
                            <div class="mgmt-card-icon icon-green">
                                <i class="fa fa-id-badge"></i>
                            </div>
                            <div>
                                <h3 class="mgmt-card-title">Manage Team</h3>
                                <span class="mgmt-card-tag text-success">Core Committee</span>
                            </div>
                        </div>
                        <p class="mgmt-card-desc">
                            Review and maintain executive board members, department co-leads, and club committee rosters.
                        </p>
                        <div class="mgmt-card-actions">
                            <a href="/team" class="btn-card-action btn-card-primary">
                                <span><i class="fa fa-users mr-2"></i>View Team Showcase</span>
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recent Activity / Items Row -->
            <div class="row" data-aos="fade-up" data-aos-duration="1000">
                <!-- Recent Events Table -->
                <div class="col-lg-6 mb-4">
                    <div class="admin-section-card h-100">
                        <div class="section-header">
                            <h4 class="section-title">
                                <i class="fa fa-calendar-o text-primary"></i>
                                Recent Events
                            </h4>
                            <a href="/events" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table admin-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentEvents as $ev)
                                        <tr>
                                            <td class="fw-semibold">{{ \Illuminate\Support\Str::limit($ev->title, 24) }}</td>
                                            <td><span class="badge-status badge-event">{{ $ev->date }}</span></td>
                                            <td class="text-end">
                                                <a href="/events/{{ $ev->id }}/edit" class="btn btn-sm btn-outline-secondary rounded-circle" title="Edit Event">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="/events/{{ $ev->id }}" class="btn btn-sm btn-outline-primary rounded-circle ml-1" title="View Event">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No events found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent News Table -->
                <div class="col-lg-6 mb-4">
                    <div class="admin-section-card h-100">
                        <div class="section-header">
                            <h4 class="section-title">
                                <i class="fa fa-newspaper-o text-danger"></i>
                                Recent News Articles
                            </h4>
                            <a href="/news" class="btn btn-sm btn-outline-danger rounded-pill px-3">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table admin-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentNews as $nw)
                                        <tr>
                                            <td class="fw-semibold">{{ \Illuminate\Support\Str::limit($nw->title, 28) }}</td>
                                            <td><span class="badge-status badge-published">{{ $nw->category ?? 'Tech' }}</span></td>
                                            <td class="text-end">
                                                <a href="/news/{{ $nw->id }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Read Article">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No news articles found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>
