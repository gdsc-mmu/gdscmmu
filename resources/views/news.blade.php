<x-layout>
    <link href="{{asset('css/newsletter.css')}}" rel="stylesheet">

    <section id="newsHubPattern" class="gdg-pattern-geometric">
        <div class="news-hero">
            <div class="container-fluid px-lg-5 text-center" data-aos="fade-down">
                <h1 class="display-4 fw-bold gdg-title">News Flash!</h1>
                <p class="news-hero-subtitle">Stay updated with the latest tech news from GDGoC@MMU community!</p>
            </div>
        </div>

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
                    @endphp
                    <div class="col-md-4 news-item" data-news-category="{{ $normalizedCategory }}" data-aos="fade-up" data-aos-delay="100">
                    
                    <div class="news-entry-card h-100">
                            

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
                                <img src="{{ $item->news_img[0] ?? '' }}" alt="News Image" class="card-img-top mb-3" style="height: 200px; object-fit: cover;">
                            </div>

                            <h4 style="font-size: 19px;" class="card-title">{{$item->title}}</h4>
                            <p style="font-size:15px" class="mt-3">{{$item->description}}</p>
                            <p class="text-muted" style="font-size: 13px;">Date created: {{ $item->date->format('F j, Y') }}</p>
                            <a  href='/news/{{$item->id}}' class="stretched-link">Read Full Story →</a>
                            
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