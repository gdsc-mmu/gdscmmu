<x-layout>
    <div class="d-flex align-items-center justify-content-center flex-column min-vh-100">
        <div class="card p-4" style="max-width: 600px;">
            <h3 class="card-title mb-4">Edit Event</h3>
            <form action="/events/{{$event->id}}/edit" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{$event->title}}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{$event->description}}</textarea>
                </div>
                <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                <input type="hidden" name="time" value="{{ now()->format('H:i:s')}}">
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>