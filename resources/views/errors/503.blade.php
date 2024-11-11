@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message')
<div class="error-container">
    <div class="error-image">
        <img src="{{ asset('images/errors/503-onsen.png') }}" alt="Closed Onsen">
    </div>
    <p>只今休業中です！(Currently on break!)</p>
    <p>Our service is taking a relaxing dip in the onsen. We'll be back refreshed and ready to serve you soon!</p>
</div>
<div class="text-center my-5">
    <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
</div>
@endsection
