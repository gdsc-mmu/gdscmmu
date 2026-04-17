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
                        <button type="button" class="news-category-pill" data-news-filter="ai-ml">AI/ML</button>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4 news-item" data-news-category="software-engineering" data-aos="fade-up" data-aos-delay="100">
                        <div class="news-entry-card h-100">
                            <div class="card-tag tag-blue">Software Engineering</div>
                            <div class="entry-icon text-primary"><i class="bi bi-stars"></i></div>
                            <h4>JoPPP HKL Platform</h4>
                            <p>Our LMS ecosystem for Hospital Kuala Lumpur is now live. Built with React and Supabase.</p>
                            <a href="#" class="stretched-link">Read Full Story →</a>
                        </div>
                    </div>

                    <div class="col-md-4 news-item" data-news-category="cybersecurity" data-aos="fade-up" data-aos-delay="200">
                        <div class="news-entry-card h-100">
                            <div class="card-tag tag-green">Cybersecurity</div>
                            <h4 class="mb-4">Recent Activity</h4>
                            <div class="news-feed-wrapper">
                                <div class="feed-item"><div class="feed-date">2 Hours Ago</div><p class="feed-text">SR Automate updated to v11.</p></div>
                                <div class="feed-item"><div class="feed-date">Yesterday</div><p class="feed-text">Gopher-Watch TUI build released.</p></div>
                                <div class="feed-item border-0"><div class="feed-date">16 Apr</div><p class="feed-text">Volunteer onboarding started.</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 news-item" data-news-category="ai-ml" data-aos="fade-up" data-aos-delay="300">
                        <div class="news-entry-card h-100">
                            <div class="card-tag tag-yellow">AI/ML</div>
                            <div class="entry-icon text-warning"><i class="bi bi-folder2-open"></i></div>
                            <h4>Workshop Assets</h4>
                            <p>Access the latest slide decks and sample code for our Cloud Native sessions.</p>
                            <a href="#" class="read-more-link small">Download Slides →</a>
                        </div>
                    </div>
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