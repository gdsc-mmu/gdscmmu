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
                        <h2 class="signin-title">Set New Password</h2>
                        <p class="signin-subtitle">Create a new secure password for your account</p>
                    </div>

                    <!-- Reset Password Form -->
                    <form action="/recovery/{{ $token }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-lock input-icon"></i>
                                <input type="password" 
                                       class="form-control custom-input @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your new password" 
                                       required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="input-icon-wrapper">
                                <i class="fa fa-shield input-icon"></i>
                                <input type="password" 
                                       class="form-control custom-input @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirm your new password" 
                                       required>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1"><i class="fa fa-exclamation-circle mr-1"></i>{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-signin">
                            <span>Update Password</span>
                            <i class="fa fa-check ml-1"></i>
                        </button>
                    </form>
                </div>

                <!-- Footer Navigation -->
                <div class="login-footer text-center mt-4">
                    <p class="signup-prompt mb-2">
                        Back to <a href="/login" class="signup-link">Sign In</a>
                    </p>
                    <a href="/" class="back-home-link">
                        <i class="fa fa-angle-left mr-1"></i> Back to Homepage
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
