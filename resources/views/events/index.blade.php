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
        @if (Auth::check())
        <div class="buttons-container">
            <a href="/events/edit-page" class="btn find-out-more mt-4">Edit Events</a>
        </div>
        <div class="buttons-container">
            <a href="/events/create" class="btn find-out-more mt-4">Create New Event</a>
        </div>
        @endif
    </div>
</div>


    <div id="events">
    @foreach ($event as $event) 

<x-event class="event-odd">
    <div class="col-md-6 d-flex justify-content-center align-items-center">
    <img src="{{ asset('images/' . $event->image) }}" class="img-fluid rounded workshop-graphic"
            alt="Webdev poster">
    </div>
    <div class="col-md-6 mt-4 mb-4 d-flex flex-column justify-content-center">
        <h3 class="mb-4">{{ $event->title }}</h3>
        <p>{{ $event->description }}</p>
        <a href="/events/{{$event->id}}">
            <button class="btn btn-warning">
                Read More!
            </button>
        </a>
    </div>
</x-event>
@endforeach

        <!-- add new events here -->
        <!-- 
        <x-event class="event-odd">
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <img src="{{asset('assets/img/jsweek.png')}}" class="img-fluid rounded workshop-graphic"
                    alt="Webdev poster">
            </div>
            <div class="col-md-6 mt-4 mb-4 d-flex flex-column justify-content-center">
                <h3 class="mb-4">Javascript Week 2021</h3>
                <a href="/jsweek.html">
                    <button class="btn btn-warning">
                        Read More!
                    </button>
                </a>
            </div>
        </x-event>

        <x-event class="event-even">
            <div class="col-md-6 d-flex justify-content-center align-items-center">
               <img src="https://via.placeholder.com/350x150.png" alt="placeholder image"> 
                <img src="{{asset('assets/img/puansitievent.png')}}" class="img-fluid rounded workshop-graphic"
                    alt="Webdev poster">
            </div>
            <div class="col-md-6 mb-4 d-flex flex-column justify-content-center">
                <h3 class="mb-4">Learn the Secrets Of The IT Industry!</h3>
                <p>
                    Tips to kickstart your career in tech by the speaker, Ms. Siti Hafsah Mohd Desa, The Head of
                    IT at Malaysia Airlines.
                </p>
                <p>
                    Join us to learn about how Madam Siti became the Head of IT in MAS without an IT degree and
                    stock up your toolbox for a career
                    in the ever-changing world of technology.
                    The following are the event details:
                <ul>
                    <li>Date: 21st January 2021</li>
                    <li>Time: 8:00p.m. - 9:30p.m.</li>
                    <li>Platform: Google Meet</li>
                </ul>
                </p>

                <a href="https://dsc.community.dev/e/mcjg2d/" target="_">
                    <button class="btn find-out-more mt-4 mr-4">
                        Sign Up
                    </button>
                </a>

            </div>
        </x-event>

        <x-event class="event-odd">
            <div class="col-md-6 mb-4 d-flex flex-column justify-content-center">
                <h3 class="mb-4">Fluttered With Flutter</h3>
                <p>
                    Wanna learn how to build native cross platform apps?
                    Sign up for our Flutter workshop series!

                    "Fluttered with Flutter" is an introductory Flutter workshop series organized by DSC@MMU,
                    with 3 sessions.

                    We've invited two Google Developer Experts on Flutter:
                <ul>
                    <li>Muhammad Haris Bin Samingan, a Flutter developer and YouTuber in Singapore (P/S: He
                        teaches Flutter on his <a href="https://www.youtube.com/channel/UChMjpXwPFzK6x82qz-R_TEQ"
                            target="_">YouTube</a> channel, go check it out)</li>
                    <li>Joshua de Guzman, a software engineer at Better Bank - Brooklyn, New York</li>
                </ul>
                This workshop series is beginner friendly, as we'll go from the fundamentals of programming with
                Dart, to having a basic understanding of Flutter.
                </p>

                <a href="https://dsc.community.dev/events/details/developer-student-clubs-multimedia-university-presents-fluttered-with-flutter-session-2/"
                    target="_">
                    <button class="btn find-out-more mt-4 mr-4">
                        Sign Up
                    </button>
                </a>

            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <img src="https://via.placeholder.com/350x150.png" alt="placeholder image"> 
                <img src="{{asset('assets/img/flutter-workshop-graphic.png')}}"
                    class="img-fluid rounded workshop-graphic" alt="Webdev poster">
            </div>
        </x-event>



        <x-event class="event-even">
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                 <img src="https://via.placeholder.com/350x150.png" alt="placeholder image"> 
                <img src="{{asset('assets/img/webdev_ig-01.png')}}" class="img-fluid rounded workshop-graphic"
                    alt="Webdev poster">
            </div>

            <div class="col-md-6 mb-4 d-flex flex-column justify-content-center">
                <h3 class="mb-4">Full Stack Web Development</h3>
                <p>
                    Wanna learn full stack website development, from frontend to backend? Sign up for this
                    workshop series!
                    <br>
                    <br>
                    Here are the pre-requisites for this workshop series:
                <ul class="prereqs">
                    <li>basic understanding of HTML, CSS</li>
                    <li>JavaScript (variables, loops, functions, classes, JSON notation)</li>
                    <li>fundamental modern JavaScript (ES6)</li>
                    <li>basic understanding of the client-server model</li>
                </ul>
                Don't worry if you don't have a good grasp of the pre-requisites,
                after watching the following two videos, you'll be ready for the workshop series.
                <ul class="prereqs">
                    <li><a href="https://www.youtube.com/watch?v=XQs5KcUj-Do" target="_">Video 1 : Git, HTML,
                            CSS</a></li>
                    <li><a href="https://www.youtube.com/watch?v=xMs4ER1rcLg" target="_">Video 2 : JavaScript
                            Basics</a></li>
                </ul>
                </p>
                <a href="https://forms.gle/WCFcsWnvaPG9sZWA9" target="_"><button class="btn find-out-more mt-4 mr-4">
                        Sign Up
                    </button></a>
            </div>

        </x-event> -->



        <!-- <div class="event event-even container-fluid">
            <div data-aos="fade-down" data-aos-duration="1500" class="container">
                <div class="row text-center text-md-left about-box">
                    <div class="col-md-6 d-flex align-items-center">
                      <img src="https://via.placeholder.com/350x150.png" alt="placeholder image">
                    </div>
                    <div class="col-md-6 mb-4 d-flex flex-column justify-content-center">
                        <h3 class="mb-4">Get a Glimpse of Google's Hiring Process (With Marcus Foon)</h3>
                        <p>
                          Keynote Presenter: Marcus Foon, Program Manager, Google Singapore
                        </p>
                        <p>
                          Wanna know how the hiring process is like at Google Singapore?
                          <br/>
                          Curious about how to join Google?
                        </p>
                          Attend this session where Marcus talks about the two topics above, and gain insights on ways to get a head-start in your career path in technology.</p>
                        <a href="https://forms.gle/shPhxa7mvCydmA8W6" target="_"><button class="btn find-out-more mt-4 mr-4">
                            Sign Up
                          </button></a>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- <div class="event event-odd container-fluid">
            <div data-aos="fade-down" data-aos-duration="1500" class="container">
                <div class="row text-center text-md-left about-box">
                    <div class="col-md-6 mb-4 d-flex flex-column justify-content-center">
                        <h3 class="mb-4">Full Stack Web Development</h3>
                        <p>Add write up here</p>
                        <a href="https://forms.gle/shPhxa7mvCydmA8W6" target="_"><button class="btn find-out-more mt-4 mr-4">
                            Sign Up
                          </button></a>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                      <img src="https://via.placeholder.com/350x150.png" alt="placeholder image">
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <!-- <div data-aos="fade-down" data-aos-duration="1500" class="container">
        <div class="row about-box">
          <div class="col-md-6 mb-4 d-flex flex-column justify-content-center">

          </div>
          <div class="col-md-6 d-flex align-items-center">

          </div>
        </div>
    </div> -->
    <div class="event event-even container-fluid align-items-center text-center">
        <div data-aos="fade-down" data-aos-duration="1500" class="container">
            <h3 class="mb-4">Future Events</h2>
                <div id="calendar"></div>
        </div>
    </div>

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
</x-layout>
