<x-layout>
    <link href="{{asset('css/newsdetails.css')}}" rel="stylesheet">

    <section id="newsHubPattern" class="gdg-pattern-geometric">
        <div class="news-hero mb-5">
            <div class="container-fluid px-lg-5 text-center" data-aos="fade-down">
                <h1 class="display-4 fw-bold gdg-title">{{ $newsbyId->title }}</h1>
                <p class="news-hero-subtitle mt-3">{{ $newsbyId->description }}</p>
              <div class="news-footer-info text-center mt-4 mb-5">
                <p class="news-hero-author">- {{ $newsbyId->author }} -</p>
                <p class="text-muted">Last Updated: {{ $newsbyId->date->format('d M Y') }} </p>
              </div>
            </div>
         <div id="newsSection"  class="news-surface mt-5 mb-5">
         <div id="newsParagraph">
        
        <!--------this is used to display each paragraph of the news article , use this if the data is an array or json format ------->
        @foreach ( $newsbyId->paragraph as $index=> $paragraph )

               
                
                   
                     
                    <div class="text-center mb-4">
                      @if($index === 0 && !empty($newsbyId->news_img[0]))
                        <img src="{{ $newsbyId->news_img[0] }}" alt="News Image" class="news-detail-image mb-4" style="max-width: 70%; height: auto; border-radius: 8px;">
                      @endif
                      <p class="news-paragraph" >
                            {{ $paragraph }}
                       </p>

                </div>
             
            
        
          
        @endforeach

        @if (!empty($newsbyId->news_source))
          <div class="text-center mt-4 mb-4">
            <p>Source: <a href="{{ $newsbyId->news_source }}" target="_blank" class="text-primary">Link</a></p>
          </div>
        @endif
        <!----------------------------------------------------------->
           
              <div class="text-center">
              <p>Got any questions about this article? Feel free to ask!</p>
              <a href="https://discord.gg/gdscmmu" class="btn btn-primary mt-3">Join Our Discord</a>
              </div>
            </div>
         </div>
         </div>

  
    </section>


        
    <script>
       
    </script>
</x-layout>