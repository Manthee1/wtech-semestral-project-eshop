@extends('frontend.layout')

@section('title', '404')

@section('content')

    {{-- 404 with a home button. NO BOOTSRAP --}}
    <section class="error-page">
        <h1>403</h1>
        <h2>Forbidden</h2>
        <p>Sorry, you are not allowed to access this page.</p>
        <a href="{{ route('home') }}" class="button button-filled">Go Home!</a>
    </section>

@endsection
