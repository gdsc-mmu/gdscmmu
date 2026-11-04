<x-layout>
    <link href="{{asset('css/newsletter.css')}}" rel="stylesheet">

    <section id="newsHubPattern" class="gdg-pattern-geometric">
        <div class="news-hero">
            <div class="container-fluid px-lg-5 text-center" data-aos="fade-down">
                <h1 class="display-4 fw-bold gdg-title">News Flash!</h1>
                <p class="news-hero-subtitle">Stay updated with the latest tech news from GDGoC@MMU community!</p>
            </div>
        </div>

        @if(auth()->check() && (auth()->user()->user_type === 'committee'))
            <div class="container-fluid px-lg-5 mb-3" data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded-4 shadow-sm border border-light-subtle flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            <i class="fa fa-shield me-1"></i> Committee Access
                        </span>
                        <span class="text-dark fw-semibold small">You have permissions to add, edit, and delete news feeds.</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="/admin/dashboard#manage-news-section" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="fa fa-pencil-square-o me-1"></i> Manage News in Admin
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div id="newsSection" class="news-surface">
            <div class="container-fluid px-lg-5">
                <div class="text-center mb-5" data-aos="fade-down">
                    <div class="news-category-list mt-4" aria-label="News categories">
                        <button type="button" class="news-category-pill is-active" data-news-filter="all">All</button>
                        <button type="button" class="news-category-pill" data-news-filter="software-engineering">Software Engineering</button>
                        <button type="button" class="news-category-pill" data-news-filter="cybersecurity">Cybersecurity</button>
                        <button type="button" class="news-category-pill" data-news-filter="ai">AI/ML</button>
                    </div>
                </div>
              

          
                <div class="row g-4">
                    @forelse($allNews as $item)
                    @php
                        // normalize category for consistent filtering
                        $normalizedCategory = strtolower(trim((string) ($item->category ?? '')));

                        // replace spaces and special characters with hyphens
                        $normalizedCategory = preg_replace('/[^a-z0-9]+/', '-', $normalizedCategory);
                        $normalizedCategory = trim($normalizedCategory, '-');

                        if (in_array($normalizedCategory, ['ai-ml', 'artificial-intelligence'], true)) {
                            $normalizedCategory = 'ai';
                        }

                        $coverSrc = !empty($item->news_img[0]) ? (str_starts_with($item->news_img[0], 'http') ? $item->news_img[0] : asset($item->news_img[0])) : asset('assets/img/developer-logo.png');
                    @endphp
                    <div class="col-md-4 news-item" data-news-category="{{ $normalizedCategory }}" data-aos="fade-up" data-aos-delay="100">
                    
                    <div class="news-entry-card h-100 position-relative">
                            

                            @switch($normalizedCategory)
                                @case('software-engineering')
                                    <div class="card-tag bg-primary text-white"><i class="bi bi-code-slash"></i> {{$item->category}}</div>
                                    @break
                                @case('cybersecurity')
                                    <div class="card-tag bg-danger text-white"><i class="bi bi-shield-lock"></i> {{$item->category}}</div>
                                    @break
                                @case('ai')
                                    <div class="card-tag bg-success text-white"><i class="bi bi-robot"></i> {{$item->category}}</div>
                                    @break
                                @default
                                    <div class="card-tag bg-secondary text-white"><i class="bi bi-stars"></i> {{$item->category}}</div>
                            @endswitch

                            <div class="card-body d-flex flex-column">
                                <img src="{{ $coverSrc }}" alt="News Image" class="card-img-top mb-3" style="height: 200px; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('assets/img/developer-logo.png') }}';">
                            </div>

                            <h4 style="font-size: 19px;" class="card-title">{{$item->title}}</h4>
                            <p style="font-size:15px" class="mt-3">{{$item->description}}</p>
                            <p class="text-muted" style="font-size: 13px;">Date created: {{ $item->date ? $item->date->format('F j, Y') : 'Recent' }}</p>
                            <a href='/news/{{$item->id}}' class="stretched-link">Read Full Story →</a>

                            @if(auth()->check() && (auth()->user()->user_type === 'committee'))
                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center position-relative" style="z-index: 5;">
                                    <a href="/admin/dashboard#news-item-{{ $item->id }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0 small" style="font-size: 0.78rem;">
                                        <i class="fa fa-pencil me-1"></i> Manage
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete \'{{ addslashes($item->title) }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0 small" style="font-size: 0.78rem;" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
               @empty
                <div class="text-center py-5" data-aos="fade-up">
                    <h3 class="text-muted">No news articles available at the moment. Check back later!</h3>
                </div>
               @endforelse

                   
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterButtons = document.querySelectorAll('[data-news-filter]');
            const newsItems = document.querySelectorAll('.news-item');

            filterButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const selectedFilter = button.getAttribute('data-news-filter');

                    filterButtons.forEach(function (btn) {
                        btn.classList.toggle('is-active', btn === button);
                    });

                    newsItems.forEach(function (item) {
                        const itemCategory = item.getAttribute('data-news-category');
                        const isVisible = selectedFilter === 'all' || itemCategory === selectedFilter;
                        item.classList.toggle('d-none', !isVisible);
                    });
                });
            });
        });
    </script>
</x-layout>