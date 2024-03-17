<div {{$attributes->merge(['class' => 'event container-fluid'])}}>
    <div data-aos="fade-down" data-aos-duration="1500" class="container">
        <div class="row text-center text-md-left about-box">
            {{$slot}}
        </div>
    </div>
</div>
