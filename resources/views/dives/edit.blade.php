<x-layout>
    <link rel="stylesheet" href="{{asset("css/dives.css")}}">
    <div class="container-fluid" style="padding-top: 40px;"> 
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5" data-aos="fade-down" data-aos-duration="1500"> 
                <!-- Edit Event Form -->
                <div class="card p-4">
                    <h3 class="card-title mb-4 text-center fs-4">Edit Dives</h3>
                    <form action="/dives/{{$dive->id}}/edit" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="mb-3" data-aos="fade-up"> 
                            <label for="title" class="form-label fs-5">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{$dive->title}}" required>
                        </div>
                        <div class="mb-3" data-aos="fade-up">
                            <label for="description" class="form-label fs-5">Description</label>
                            <textarea class="form-control" id="description" name="description" wrap="hard" rows="8" required>{{$dive->description}}</textarea>
                        </div>
                        @if($dive->image)
                        <div class="mb-3" data-aos="fade-up">
                            <label class="fs-5">Previous Image:</label><br>
                            {{--if image string starts with https,--}}
                            <img src="{{Str::startsWith($dive->image, 'https') ? $dive->image : asset('image/' . $dive-image)}}" alt="Previous Image" style="max-width: 300px;">
                        </div>
                        @endif
                        <div class="mb-3" data-aos="fade-up">
                            <label for="image" class="form-label fs-5">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
