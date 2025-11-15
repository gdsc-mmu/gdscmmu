<x-layout>
   <div  id="membership" class="bg-white fade-down container-fluid">
    <div  class="p-5">
    <h1 class="text-center  mt-1" data-aos="fade-down "  data-aos-duration="1000">Check Your Membership Here!</h1>
    <p class="text-center mt-5">Enter your name and year of studies below!</p>
     <div class="d-flex align-items-center justify-content-center min-vh-100">
    <form action="" class="d-flex flex-column p-4 border rounded shadow-sm" style="width: 300px;">
        @csrf
        <label for="name" class="mb-2">Name:</label>
        <input type="text" id="name" name="name" class="form-control mb-3">

        <label for="year" class="mb-2">Year of Studies:</label>
        <input type="text" id="year" name="year" class="form-control mb-3">

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
    </div>
</x-layout>