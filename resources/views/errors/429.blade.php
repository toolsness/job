@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message')
<div class="error-container">
    <div class="error-image">
        <img src="{{ asset('images/errors/429-bullet-train.png') }}" alt="Overcrowded Bullet Train">
    </div>
    <p>混んでいます！(It's crowded!)</p>
    <p>Our server is as packed as a Tokyo rush hour train. Please wait for the next one!</p>
</div>
<div class="text-center my-5">
    <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
</div>
@endsection
