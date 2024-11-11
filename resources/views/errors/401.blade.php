@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message')
<div class="error-container">
    <div class="error-image">
        <img src="{{ asset('images/errors/401-sushi.png') }}" alt="Unauthorized Sushi">
    </div>
    <p>おっと！この寿司は許可されていません！(Oops! This sushi is not authorized!)</p>
    <p>You need the right chopsticks to access this roll.</p>

</div>
<div class="text-center my-5">
    <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
</div>
@endsection
