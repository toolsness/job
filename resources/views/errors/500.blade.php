@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message')
<div class="error-container">
    <div class="error-image">
        <img src="{{ asset('images/errors/500-godzilla.png') }}" alt="Godzilla Attack">
    </div>
    <p>サーバーがゴジラに襲われました！(Server attacked by Godzilla!)</p>
    <p>Our servers are experiencing a kaiju-sized problem. We're sending in the repair robots!</p>
</div>
<div class="text-center my-5">
    <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
</div>
@endsection
