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
                        <h2 class="signin-title">Reset Password</h2>
                        <p class="signin-subtitle">Enter your email to receive a recovery link</p>
                    </div>

                    <!-- Password Recovery Form -->
                    <form action="/recovery" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-envelope input-icon"></i>
                                <input type="email" 
                                       class="form-control custom-input @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{old('email')}}" 
                                       placeholder="Enter your registered email" 
                                       required 
                                       autocomplete="email">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-signin">
                            <span>Send Recovery Link</span>
                            <i class="fa fa-paper-plane ml-1"></i>
                        </button>
                    </form>
                </div>

                <!-- Footer Navigation -->
                <div class="login-footer text-center mt-4">
                    <p class="signup-prompt mb-2">
                        Remembered your password? <a href="/login" class="signup-link">Sign In</a>
                    </p>
                    <a href="/" class="back-home-link">
                        <i class="fa fa-angle-left mr-1"></i> Back to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
