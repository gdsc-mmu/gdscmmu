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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
    <x-sidebar />
    {{$slot}}

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

    <script src="{{asset('js/sidebar.js')}}"></script>
    <script> AOS.init(); </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
