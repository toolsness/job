@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message')
<div class="error-container">
    <div class="error-image">
        <img src="{{ asset('images/errors/403-sumo.png') }}" alt="Sumo Wrestler Blocking">
    </div>
    <p>立入禁止！(No entry!)</p>
    <p>Our sumo wrestler is blocking your way. You shall not pass!</p>
</div>
<div class="text-center my-5">
    <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
</div>
@endsection
