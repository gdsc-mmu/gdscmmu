<!doctype html>
<html lang="en">
<!-- __      __.__            __                                                     .___     .__                 .__                           _________
  /  \    /  \  |__ _____ _/  |_  _____ _______   ____    ___.__. ____  __ __    __| _/____ |__| ____    ____   |  |__   ___________   ____   \_____   \
  \   \/\/   /  |  \\__  \\   __\ \__  \\_  __ \_/ __ \  <   |  |/  _ \|  |  \  / __ |/  _ \|  |/    \  / ___\  |  |  \_/ __ \_  __ \_/ __ \     /   __/
   \        /|   Y  \/ __ \|  |    / __ \|  | \/\  ___/   \___  (  <_> )  |  / / /_/ (  <_> )  |   |  \/ /_/  > |   Y  \  ___/|  | \/\  ___/    |   |
    \__/\  / |___|  (____  /__|   (____  /__|    \___  >  / ____|\____/|____/  \____ |\____/|__|___|  /\___  /  |___|  /\___  >__|    \___  >   |___|
         \/       \/     \/            \/            \/   \/                        \/              \//_____/        \/     \/            \/    <___>
  .___  _____                         __                                  .__            __                       /\
  |   |/ ____\  ___.__. ____  __ __  |  | __ ____   ______  _  __ __  _  _|  |__ _____ _/  |_   ___.__. ____  __ _)/______   ____
  |   \   __\  <   |  |/  _ \|  |  \ |  |/ //    \ /  _ \ \/ \/ / \ \/ \/ /  |  \\__  \\   __\ <   |  |/  _ \|  |  \_  __ \_/ __ \
  |   ||  |     \___  (  <_> )  |  / |    <|   |  (  <_> )     /   \     /|   Y  \/ __ \|  |    \___  (  <_> )  |  /|  | \/\  ___/
  |___||__|     / ____|\____/|____/  |__|_ \___|  /\____/ \/\_/     \/\_/ |___|  (____  /__|    / ____|\____/|____/ |__|    \___  >
                \/                        \/    \/                             \/     \/        \/                              \/
      .___     .__                                                       __       .__
    __| _/____ |__| ____    ____        ____  ____   _____   ____       |__| ____ |__| ____
   / __ |/  _ \|  |/    \  / ___\     _/ ___\/  _ \ /     \_/ __ \      |  |/  _ \|  |/    \
  / /_/ (  <_> )  |   |  \/ /_/  >    \  \__(  <_> )  Y Y  \  ___/      |  (  <_> )  |   |  \
  \____ |\____/|__|___|  /\___  / /\   \___  >____/|__|_|  /\___  > /\__|  |\____/|__|___|  /
       \/              \//_____/  )/       \/            \/     \/  \______|              \/
    __  .__               __                .__           .__              .__      __
  _/  |_|  |__   ____   _/  |_  ____   ____ |  |__   ____ |__| ____ _____  |  |   _/  |_  ____ _____    _____
  \   __\  |  \_/ __ \  \   __\/ __ \_/ ___\|  |  \ /    \|  |/ ___\\__  \ |  |   \   __\/ __ \\__  \  /     \
   |  | |   Y  \  ___/   |  | \  ___/\  \___|   Y  \   |  \  \  \___ / __ \|  |__  |  | \  ___/ / __ \|  Y Y  \
   |__| |___|  /\___  >  |__|  \___  >\___  >___|  /___|  /__|\___  >____  /____/  |__|  \___  >____  /__|_|  /  /\
             \/     \/             \/     \/     \/     \/        \/     \/                  \/     \/      \/   \/                                      -->

<head>
    <title>GDSC@MMU</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset("assets/img/developer-logo.png")}}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,400;1,700&display=swap"
        rel="stylesheet">
    <link href="{{asset("css/style.css")}}" rel="stylesheet">
    <link href="{{asset("css/navbar.css")}}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CCS3W88SJN"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-CCS3W88SJN');
    </script>
</head>

<body>
    <nav id="sidebar">
        <div id="dismiss">
            <i class="fa fa-arrow-left"></i>
        </div>

        <div class="sidebar-header">
            <h3>GDSC@MMU</h3>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a class="navbar-link" href="#">Home</a>
            </li>

            <li>
                <a class="normal-nav-link" href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">About</a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li>
                        <a class="navbar-link" href="#moreondsc">Who We Are</a>
                    </li>
                    <li>
                        <a class="navbar-link" href="#whattoexpect">What To Expect</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="navbar-link" href="team">Meet the Team</a>
            </li>
            <li>
                <a class="navbar-link" href="events">Events</a>
            </li>
            <li>
                <a class="navbar-link" href="#footer">Contact Us</a>
            </li>

        </ul>
        <div class="social-handles">
            <a href="https://github.com/GDSCMMU" target="_"><i class="fa fa-github fa-2x" aria-hidden="true"></i></a>
            <a href="https://www.facebook.com/dscmmu" target="_"><i class="fa fa-facebook fa-2x"
                    aria-hidden="true"></i></a>
            <a href="https://www.linkedin.com/in/dsc-mmu-123a261ba/" target="_"><i class="fa fa-linkedin fa-2x"
                    aria-hidden="true"></i></a>
            <a href="https://www.youtube.com/channel/UCSonnieKvZDRUrKmzzUdv1w?" target="_"><i
                    class="fa fa-youtube-play fa-2x" aria-hidden="true"></i></a>
            <a href="mailto:lead.dscmmu@gmail.com"><i class="fa fa-envelope fa-2x" aria-hidden="true"></i></a>
            <a href="https://twitter.com/DscMmu" target="_"><i class="fa fa-twitter fa-2x" aria-hidden="true"></i></a>
        </div>

    </nav>

    <button type="button" id="sidebarCollapse" class="btn">
        <i class="navbar-toggle fa fa-bars fa-2x"></i>
    </button>

    <div class="overlay"></div>
    <header>
        <div class="container">

            <nav data-aos="fade-down" class="nav d-flex justify-content-between align-items-center">

                <ul class="main-links list-unstyled d-flex">
                    <li><a href="/" class="nav-link">Home</a></li>
                    <li><a href="#moreondsc" class="nav-link">About</a></li>
                    <li><a href="/team" class="nav-link">Team</a></li>
                    <li><a href="/events" class="nav-link">Events</a></li>
                    <li><a href="#footer" class="nav-link">Contact Us</a></li>
                </ul>

                <div class="spacer">
                </div>

                <div class="social-handles">
                    <a href="https://github.com/GDSCMMU" target="_"><i class="fa fa-github fa-2x"
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

        </div>
    </header>


    {{$slot}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#dismiss, .overlay, .navbar-link').on('click', function () {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').addClass('active');
                $('.overlay').addClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });
    </script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>

</html>
