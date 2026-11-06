<x-layout>
    <link href="{{asset('css/newsdetails.css')}}" rel="stylesheet">

    <section id="newsHubPattern" class="gdg-pattern-geometric">
        <div class="news-hero mb-5">
            <div class="container-fluid px-lg-5 text-center" data-aos="fade-down">
                <h1 class="display-4 fw-bold gdg-title">{{ $newsbyId->title }}</h1>
                <p class="news-hero-subtitle mt-3">{{ $newsbyId->description }}</p>
              <div class="news-footer-info text-center mt-4 mb-5">
                <p class="news-hero-author">- {{ $newsbyId->author }} -</p>
                <p class="text-muted">Last Updated: {{ $newsbyId->date ? $newsbyId->date->format('d M Y') : 'Recent' }} </p>
                @if(auth()->check() && (auth()->user()->user_type === 'committee'))
                  <div class="mt-2">
                    <a href="/admin/dashboard#news-item-{{ $newsbyId->id }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                      <i class="fa fa-pencil me-1"></i> Edit Article in Admin
                    </a>
                  </div>
                @endif
              </div>
            </div>
         <div id="newsSection"  class="news-surface mt-5 mb-5">
         <div id="newsParagraph">
        
        @php
            $paragraphs = is_array($newsbyId->paragraph) ? $newsbyId->paragraph : (!empty($newsbyId->paragraph) ? [$newsbyId->paragraph] : [$newsbyId->description]);
            $images = is_array($newsbyId->news_img) ? $newsbyId->news_img : [];
        @endphp
        <!--------this is used to display each paragraph of the news article , use this if the data is an array or json format ------->
        @foreach ( $paragraphs as $index=> $paragraph )
                    <div class="text-center mb-4">
                     
                        @php
                            $imagePath = null;
                            if (!empty($images[$index])) {
                                $imagePath = $images[$index];
                            } elseif ($index % 2 === 0 && isset($images[$index / 2]) && !empty($images[$index / 2])) {
                                $imagePath = $images[$index / 2];
                            }
                        @endphp

                        @if(!empty($imagePath))
                            <img src="{{ str_starts_with($imagePath, 'http') ? $imagePath : asset($imagePath) }}" alt="News Image" class="news-image mb-3" width="600" style="max-width: 100%; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
                        @endif
                     
                      <p class="news-paragraph mb-5" >
                          {{ $paragraph }}
                       </p>

                       @if($index % 2 === 1 || $loop->last)
                            </div>
                        @endif
                 
             
            
        
          
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