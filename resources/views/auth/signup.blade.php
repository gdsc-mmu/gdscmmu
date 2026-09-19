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
                        <h2 class="signin-title">Create Account</h2>
                        <p class="signin-subtitle">Join the GDGoC MMU community today</p>
                    </div>

                    <!-- Sign Up Form -->
                    <form action="/signup" method="POST" class="needs-validation" id="signupForm" novalidate>
                        @csrf

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Username</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-user input-icon"></i>
                                <input type="text" 
                                       class="form-control custom-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{old('name')}}" 
                                       placeholder="Choose a username" 
                                       required 
                                       autocomplete="username">
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-envelope input-icon"></i>
                                <input type="email" 
                                       class="form-control custom-input @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{old('email')}}" 
                                       placeholder="Enter your email address" 
                                       required 
                                       autocomplete="email">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-lock input-icon"></i>
                                <input type="password" 
                                       class="form-control custom-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Create a strong password (min 6 chars)" 
                                       required 
                                       autocomplete="new-password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-shield input-icon"></i>
                                <input type="password" 
                                       class="form-control custom-input @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Re-enter your password" 
                                       required 
                                       autocomplete="new-password">
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-signin" id="submitSignUpBtn">
                            <span>Create Account</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- Footer Navigation -->
                <div class="login-footer text-center mt-4">
                    <p class="signup-prompt mb-2">
                        Already have an account? <a href="/login" class="signup-link">Sign In</a>
                    </p>
                    <a href="/" class="back-home-link">
                        <i class="fa fa-angle-left mr-1"></i> Back to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
