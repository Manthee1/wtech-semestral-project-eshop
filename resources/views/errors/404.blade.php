@extends('frontend.layout')

@section('title', '404')

@section('content')

    {{-- 404 with a home button. NO BOOTSRAP --}}
    <section class="error-page">
        <h1>404</h1>
        <h2>Page not found</h2>
        <p>Sorry, the page you are looking for drove away.</p>
        <a href="{{ route('home') }}" class="button button-filled">Go Home!</a>
    </section>

@endsection
