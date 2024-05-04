<x-layout>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <form action="/recovery" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="email" class="text-white">Email :</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <button type="submit" class="btn border rounded text-white">Reset password</button>
        </form>
    </div>
</x-layout>
