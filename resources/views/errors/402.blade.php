@extends('errors::minimal')

@section('title', __('Payment Required'))
@section('code', '402')
@section('message', __('Payment Required'))
@extends('errors::minimal')

@section('title', __('Payment Required'))
@section('code', '402')
@section('message')
<div class="error-container">
    <div class="error-image">
        <img src="{{ asset('images/errors/402-vending-machine.png') }}" alt="Japanese Vending Machine">
    </div>
    <p>お金が足りません！(Not enough yen!)</p>
    <p>This vending machine needs a few more coins to dispense your request.</p>
</div>
<div class="text-center my-5">
    <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
</div>
@endsection
