<x-layout>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <form action="/signup" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="mb-2">
                <label for="name" class="form-label text-white">Username:</label>
                <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                @error('name')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-2">
                <label for="email" class="form-label text-white">Email:</label>
                <input type="text" class="form-control" name="email" value="{{old('email')}}" required>
                @error('email')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-2">
                <label for="password" class="form-label text-white">Password:</label>
                <input type="password" class="form-control" name="password" required>
                @error('password')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label text-white">Confirm Password:</label>
                <input type="password" class="form-control" name="password_confirmation" required>
                @error('password_confirmation')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <input type="submit" class="btn border rounded text-white" value="Register">
        </form>

        <span class="mt-5 text-white">Forgot your<a href="/recovery" class="text-decoration-none ms-1 text-warning">password?</a></span>
        <span class="text-white">Already got an Account?<a href="/login" class="text-decoration-none ms-2 text-warning">Log In</a></span>
    </div>
</x-layout>
