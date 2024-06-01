<x-layout>
    <div class="min-vh-100 d-flex justify-content-center align-items-center flex-column">
        <h1 class="text-white">Weekly Dives</h1>
        @if($dives->count())
            @foreach($dives as $dive)
                <h2 class="text-white">Dive {{$dive->id}}: {{$dive->title}}</h2>
                <p class="text-white">{{$dive->date}}</p>
                <p class="text-white">{{$dive->time}}</p>
                <p class="text-white">{{$dive->description}}</p>
            @endforeach
        @else
            <h2 class="text-white">Dive 1: Lorem velit eligendi?</h2>
            <p class="text-white">Ipsum assumenda rem voluptatibus omnis nisi. Vero enim nisi vitae!</p>
            <h2 class="text-white">Dive 2: Ipsum a voluptates.</h2>
            <p class="text-white">Consectetur voluptas libero id explicabo numquam. Quisquam repellendus deserunt veniam.</p>
            <h2 class="text-white">Dive 3: Lorem dolorum vitae</h2>
            <p class="text-white">Lorem praesentium autem dolores saepe voluptate. Perspiciatis doloremque fuga aut?</p>
        @endif
    </div>
</x-layout>
