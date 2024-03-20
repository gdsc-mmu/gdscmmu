@if(session()->has('message'))
<div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
    <p class="position-absolute top-0 start-50 translate-middle-x bg-white rounded z-3 px-4 py-1 mt-2">
        {{session('message')}}
    </p>
</div>
@endif
