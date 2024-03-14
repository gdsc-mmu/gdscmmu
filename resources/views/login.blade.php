<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        <form action="">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" required>
            <label for="username" class="form-label">Password:</label>
            <input type="password" class="form-control" required>
            <input type="submit" class="btn border rounded mt-2" value="Login">
        </form>

        <span class="mt-5">Forgot your<a href="/recovery" class="text-decoration-none ms-1">password?</a></span>
        <span>Haven't got an Account?<a href="/signup" class="text-decoration-none ms-2">Sign Up</a></span>
    </div>
</x-layout>
