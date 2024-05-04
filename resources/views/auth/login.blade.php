<x-layout>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <form action="/login" method="POST" class="needs-validation" novalidate>
            @csrf
            <label for="name" class="form-label text-white">Username:</label>
            <input type="text" class="form-control" name="name" value="{{old('name')}}" required>
            <label for="password" class="form-label text-white">Password:</label>
            <input type="password" class="form-control" name="password" required>
            <input type="submit" class="btn border rounded mt-2 text-white" value="Login">
        </form>

        <span class="mt-5 text-white">Forgot your<a href="/recovery" class="text-decoration-none ms-1 text-warning">password?</a></span>
        <span class="text-white">Haven't got an Account?<a href="/signup" class="text-decoration-none ms-2 text-warning">Sign Up</a></span>
    </div>
</x-layout>
