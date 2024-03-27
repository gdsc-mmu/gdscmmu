<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <form action="/signup" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="mb-2">
                <label for="name" class="form-label">Username:</label>
                <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
                @error('name')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-2">
                <label for="email" class="form-label">Email:</label>
                <input type="text" class="form-control" name="email" value="{{old('email')}}" required>
                @error('email')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
                @error('password')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" name="password_confirmation" required>
                @error('password_confirmation')
                <p class="text-danger">{{$message}}</p>
                @enderror
            </div>
            <input type="submit" class="btn border rounded" value="Register">
        </form>

        <span class="mt-5">Forgot your<a href="/recovery" class="text-decoration-none ms-1">password?</a></span>
        <span>Already got an Account?<a href="/login" class="text-decoration-none ms-2">Log In</a></span>
    </div>
</x-layout>
