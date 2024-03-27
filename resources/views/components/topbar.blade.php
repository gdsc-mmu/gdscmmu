<header>
    <div class="container">
        <nav data-aos="fade-down" class="nav d-flex justify-content-between align-items-center">

            <ul class="main-links list-unstyled d-flex">
                <li><a href="/" class="nav-link text-white">Home</a></li>
                <li><a href="/#moreondsc" class="nav-link text-white">About</a></li>
                <li><a href="/team" class="nav-link text-white">Team</a></li>
                <li><a href="/events" class="nav-link text-white">Events</a></li>
                <li><a href="/#footer" class="nav-link text-white">Contact Us</a></li>
                @unless(auth()->check())
                <li><a href="/login" class="nav-link text-white">Login</a></li>
                @else
                <li><a href="/logout" class="nav-link text-white">Logout</a></li>
                @endunless
            </ul>

            <div class="spacer">
            </div>

            <div class="social-handles">
                <a href="https://github.com/gdsc-mmu" target="_"><i class="fa fa-github fa-2x"
                        aria-hidden="true"></i></a>
                <a href="https://www.facebook.com/dscmmu" target="_"><i class="fa fa-facebook fa-2x"
                        aria-hidden="true"></i></a>
                <a href="https://www.linkedin.com/in/dsc-mmu-123a261ba/" target="_"><i class="fa fa-linkedin fa-2x"
                        aria-hidden="true"></i></a>
                <a href="https://www.youtube.com/channel/UCSonnieKvZDRUrKmzzUdv1w?" target="_"><i
                        class="fa fa-youtube-play fa-2x" aria-hidden="true"></i></a>
                <a href="mailto:lead.dscmmu@gmail.com"><i class="fa fa-envelope fa-2x" aria-hidden="true"></i></a>
                <a href="https://twitter.com/DscMmu" target="_"><i class="fa fa-twitter fa-2x"
                        aria-hidden="true"></i></a>
            </div>
            </ul>
        </nav>
    </div>
</header>
