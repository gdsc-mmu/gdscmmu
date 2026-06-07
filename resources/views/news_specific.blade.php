<x-layout>
    <link href="{{asset('css/newsdetails.css')}}" rel="stylesheet">

    <section id="newsHubPattern" class="gdg-pattern-geometric">
        <div class="news-hero mb-5">
            <div class="container-fluid px-lg-5 text-center" data-aos="fade-down">
                <h1 class="display-4 fw-bold gdg-title">{{ $newsbyId->title }}</h1>
                <p class="news-hero-subtitle mt-3">{{ $newsbyId->description }}</p>
              <div class="news-footer-info text-center mt-4">
                <p class="news-hero-author">- {{ $newsbyId->author }} -</p>
                <p class="text-muted">Last Updated: {{ $newsbyId->date->format('d M Y') }} </p>
              </div>
            </div>
         <div id="newsSection" class="news-surface">
            <div id="newsParagraph">
        
        <!--------this is used to display each paragraph of the news article , use this if the data is an array or json format ------->
        @foreach ( $newsbyId->paragraph as $index=> $paragraph )

                @if($newsbyId->news_img && $index === 0)
                <div class="text-center mb-4">
                    <img src="{{ $newsbyId->news_img }}" width="70%" alt="News Image" class="img-fluid news-image">
                </div>
                @endif
            
        
            <p class="mb-5">
                {{ $paragraph }}
            </p>
        @endforeach
        <!----------------------------------------------------------->
           
              <div class="text-center">
              <p>Got any questions about this article? Feel free to ask!</p>
              <a href="https://discord.gg/gdscmmu" class="btn btn-primary mt-3">Join Our Discord</a>
              </div>
            </div>
         </div>

  
    </section>


        
    <script>
       
    </script>
</x-layout>