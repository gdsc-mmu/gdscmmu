<x-layout>
    <link href="{{asset("css/addEvent.css")}}" rel="stylesheet">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div id="event-container">
            <div class="add-event-form">
                <h1 id="new-title">Add New Event</h1>
                <form method="post" action="{{ route('events.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="title" class="form-label text-white">Title</label>
                        <input type="text"  class="form-control" placeholder=" Title*" name="title" >
                    </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="text" class="form-label text-white">Description</label>
                                    <textarea id="text" class="form-control" name="description" columns="20" placeholder="Description"  ></textarea>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="image" class="form-label text-white">Insert image </label>
                                <input type="file" id="image" name="image" class="form-control">
                            </div>

                            <div class="button">
                                <button type="submit">Post</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
