<div class="container">
  <h1>Add New Event</h1>
  <section class="mt-3">
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
      <div class="card p-3 mb-3">
        <label for="floatingInput">Event Name</label>
        <input class="form-control" type="text" name="title">
      </div>
      <div class="card p-3 mb-3">
        <label for="floatingTextArea">Description</label>
        <textarea class="form-control" name="description" id="floatingTextarea" cols="30" rows="10"></textarea>
      </div>
      <div class="card p-3 mb-3">
        <label for="formFile" class="form-label">Add Image</label>
        <img src="" alt="" class="img-blog">
        <input class="form-control" type="file" name="image">
      </div>
      <div class="card p-3 mb-3">
        <button class="btn btn-secondary" type="submit">ADD EVENT</button>
      </div>
    </form>
  </section>
</div>
