<x-layout>
    <link href="{{asset("css/addEvent.css")}}" rel="stylesheet">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div id="event-container">
            <div class="add-event-form">
                <h1 id="new-title">Add New Event</h1>
                <form method="post" action="{{ route('events.store') }}" enctype="multipart/form-data">
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
                        <label for="title" class="label-right"></label>
                        <input type="text"  class="form-group" placeholder=" Title*" name="title" >
                    </div>
                        <!-- <div class="date-time-group">
                            <div class="add-was-form">
                            <div class="date">
                            <label for="date">Date *</label>
                            <input type="date" id="date" name="date" >
                            </div>
                            </div>
                            <div class="time">
                            <div class="add-was-form">
                            <label for="time">Time *</label>
                            <input type="time" id="time" name="time" >
                            </div>
                            </div>
                            </div> -->
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="text" class="label-right"></label>
                                    <textarea id="text" name="description" columns="20" placeholder="Description"  ></textarea>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="image">Insert image </label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="image" name="image" required>
                                    <span class="file-input-text"></span>
                                </div>
                            </div>
                            <div class="button">
                                <button type="submit">Post</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
