@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message')
<div class="error-container">
    <div class="error-image">
        <img src="{{ asset('images/errors/419-bonsai.png') }}" alt="Withered Bonsai">
    </div>
    <p>ページの有効期限が切れました！(Page has withered!)</p>
    <p>Like a neglected bonsai, this page has expired. Time to water it with a refresh!</p>

</div>
<div class="text-center my-5">
    <a class="rounded-lg border border-white text-white hover:bg-white hover:text-black transition-colors duration-300 border-solid px-4 py-2" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Lets go back to Home</a>
</div>
@endsection
