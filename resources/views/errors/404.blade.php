@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message')
    <div class="error-container">
        <div class="error-image">
            <img src="{{ asset('images/errors/404-ninja.png') }}" alt="Ninja Hiding">
        </div>
        <p>見つかりません！(Can't be found!)</p>
        <p>Our ninja has hidden this page too well. Even we can't find it!</p>
    </div>
    <div class="text-center my-5">
        <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
    </div>
@endsection
