<header>
    <div class="container">
        <nav data-aos="fade-down" class="nav d-flex justify-content-between align-items-center">

            <ul class="main-links list-unstyled d-flex mb-0 align-items-center">
                <li><a href="/" class="nav-link text-white">Home</a></li>
                <li><a href="/#moreondsc" class="nav-link text-white">About</a></li>
                <li><a href="/team" class="nav-link text-white">Team</a></li>
                <!--- <li><a href="/events" class="nav-link text-white">Events</a></li> --->
                <!--<li><a href="/dives" class="nav-link text-white">Dives</a></li>-->
                <li><a href="/#membership" class="nav-link text-white">Membership</a></li>
                <li><a href="/news" class="nav-link text-white">News</a></li>
                <li><a href="/#footer" class="nav-link text-white">Contact Us</a></li>
            </ul>

            <div class="spacer">
            </div>

            <div class="social-handles d-flex align-items-center">
                <a href="https://github.com/gdsc-mmu" target="_" title="GitHub"><i class="fa fa-github fa-2x"
                        aria-hidden="true"></i></a>
                <a href="https://www.linkedin.com/company/google-developer-student-club-mmu/" target="_" title="LinkedIn"><i class="fa fa-linkedin fa-2x"
                        aria-hidden="true"></i></a>
                <a href="https://www.youtube.com/@GDGoC_MMU" target="_" title="YouTube"><i
                        class="fa fa-youtube-play fa-2x" aria-hidden="true"></i></a>
                <a href="https://www.instagram.com/gdg.mmu" target="_" title="Instagram"><i class="fa fa-instagram fa-2x"
                        aria-hidden="true"></i></a>

                @if(auth()->check())
                    <a href="/admin/dashboard" class="btn-dashboard-nav"><i class="fa fa-tachometer mr-1"></i> Dashboard</a>
                    <a href="/logout" class="btn-signin-nav"><i class="fa fa-sign-out mr-1"></i> Logout</a>
                @endif
            </div>
        </nav>
    </div>
</header>
