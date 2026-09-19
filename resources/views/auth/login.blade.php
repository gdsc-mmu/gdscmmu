<x-layout>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">

    <div class="signin-page-wrapper">
        <div class="signin-container" data-aos="zoom-in" data-aos-duration="800">
            <div class="signin-card">
                <!-- 4-Color Google Accent Bar -->
                <div class="google-accent-bar">
                    <div class="bar-blue"></div>
                    <div class="bar-red"></div>
                    <div class="bar-yellow"></div>
                    <div class="bar-green"></div>
                </div>

                <div class="signin-card-body">
                    <!-- Header with GDGoC MMU Branding -->
                    <div class="signin-header">
                        <img src="{{asset('assets/img/ClubLogo.png')}}" alt="GDGoC MMU Logo" class="signin-logo">
                        <h2 class="signin-title">Admin Sign In</h2>
                        <p class="signin-subtitle">GDGoC MMU Management Portal</p>
                    </div>

                    <!-- Sign In Form -->
                    <form action="/login" method="POST" class="needs-validation" id="loginForm" novalidate>
                        @csrf

                        <!-- Username / Student ID -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Username or Email</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-user input-icon"></i>
                                <input type="text" 
                                       class="form-control custom-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{old('name')}}" 
                                       placeholder="Enter your admin credentials" 
                                       required 
                                       autocomplete="username">
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label mb-0">Password</label>
                                <a href="/recovery" class="forgot-link">Forgot password?</a>
                            </div>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-lock input-icon"></i>
                                <input type="password" 
                                       class="form-control custom-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password" 
                                       required 
                                       autocomplete="current-password">
                                <button type="button" class="btn-toggle-password" id="togglePasswordBtn" title="Toggle password visibility">
                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-4">
                            <input class="form-check-input custom-checkbox" type="checkbox" name="remember" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Keep me signed in
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-signin" id="submitSignInBtn">
                            <span id="submitBtnText">Sign In</span>
                            <i class="fa fa-arrow-right" id="submitBtnIcon"></i>
                        </button>
                    </form>
                </div>

                <!-- Footer Navigation -->
                <div class="login-footer text-center mt-4">
                    <a href="/" class="back-home-link">
                        <i class="fa fa-angle-left mr-1"></i> Back to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Frontend Interactive Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password Show/Hide Toggle
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const togglePasswordIcon = document.getElementById('togglePasswordIcon');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    togglePasswordIcon.className = isPassword ? 'fa fa-eye-slash' : 'fa fa-eye';
                });
            }

            // Form validation and submit feedback
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitSignInBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            const submitBtnIcon = document.getElementById('submitBtnIcon');

            if (loginForm) {
                loginForm.addEventListener('submit', function (event) {
                    if (!loginForm.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                        loginForm.classList.add('was-validated');
                    } else {
                        submitBtnText.innerText = 'Signing in...';
                        submitBtnIcon.className = 'fa fa-spinner fa-spin';
                    }
                });
            }
        });
    </script>
</x-layout>
