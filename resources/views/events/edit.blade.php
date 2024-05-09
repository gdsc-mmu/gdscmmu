<x-layout>
    <div class="container-fluid" style="padding-top: 40px;"> 
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5" data-aos="fade-down" data-aos-duration="1500"> 
                <!-- Edit Event Form -->
                <div class="card p-4">
                    <h3 class="card-title mb-4 text-center fs-4">Edit Event</h3>
                    <form action="/events/{{$event->id}}/edit" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="mb-3" data-aos="fade-up"> 
                            <label for="title" class="form-label fs-5">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{$event->title}}" required>
                        </div>
                        <div class="mb-3" data-aos="fade-up">
                            <label for="description" class="form-label fs-5">Description</label>
                            <textarea class="form-control" id="description" name="description" wrap="hard" rows="8" required>{{$event->description}}</textarea>
                        </div>
                        <div class="mb-3" data-aos="fade-up">
                            <label for="image" class="form-label fs-5">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        @if($event->image)
                        <div class="mb-3" data-aos="fade-up">
                            <label class="fs-5">Previous Image:</label><br>
                            <img src="{{ asset('images/' . $event->image) }}" alt="Previous Image" style="max-width: 300px;">
                        </div>
                        @endif
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
