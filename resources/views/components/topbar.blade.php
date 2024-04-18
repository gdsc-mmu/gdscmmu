
<header>
    <div class="container">
    <link href="{{asset("css/LoginPopup.css")}}" rel="stylesheet">
        <nav data-aos="fade-down" class="nav d-flex justify-content-between align-items-center">

            <ul class="main-links list-unstyled d-flex">
                <li><a href="/" class="nav-link text-white">Home</a></li>
                <li><a href="/#moreondsc" class="nav-link text-white">About</a></li>
                <li><a href="/team" class="nav-link text-white">Team</a></li>
                <li><a href="/events" class="nav-link text-white">Events</a></li>
                <li><a href="/#footer" class="nav-link text-white">Contact Us</a></li>
                @unless(auth()->check())
                <li><button id="openLogin" class="nav-link text-white">Login</button></li>
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
    <div id="loginPopup" class="login-popup">
      <h2>Login</h2>
      <input type="text" id="username" placeholder="Username">
      <input type="password" id="password" placeholder="Password">
      <button id="loginBtn">Login</button>
      <p class="blue">Forget your password?</p>
      <p>Haven’t got an account? <span class="blue">Sign up!</span></p>
      <button id="closeLogin">Close</button>
    </div>
  </div>
    
  <script>
        document.addEventListener("DOMContentLoaded", function () {
            const openLoginBtn = document.getElementById("openLogin");
            const closeLoginBtn = document.getElementById("closeLogin");
            const loginPopup = document.getElementById("loginPopup");
            const usernameInput = document.getElementById("username");
            const passwordInput = document.getElementById("password");

            openLoginBtn.addEventListener("click", function () {
                loginPopup.style.display = "block";
            });

            closeLoginBtn.addEventListener("click", function () {
                loginPopup.style.display = "none";
            });

            document.getElementById("loginBtn").addEventListener("click", function () {
                const username = usernameInput.value;
                const password = passwordInput.value;
                // Implement your login logic here
                console.log('Logging in with:', username, password);
            });

          
            loginPopup.style.display = "none";
        });
    </script>

</header>
