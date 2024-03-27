<x-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <form action="/recovery/{{ $token }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="form-group mb-3">
                <label for="password">Password :</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="password_confirmation">Password confirmation :</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            <input type="hidden" name="token" value="{{ $token }}">
            <button type="submit" class="btn btn-primary">Reset password</button>
        </form>
    </div>
</x-layout>
