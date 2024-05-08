@extends('frontend.layout')

@section('title', 'Home')

@section('content')
    <section id="banner">
        <img class="banner-image" src="./img/banner-car-backgrond.png" alt="banner">
        <div class="text-content mb-6">
            <h1 class="text-title">Ride with Confidence</h1>
            <p class="text-large">
                Where Quality Meets the Open Road.
                <br>
                Explore Our Exceptional Collection Today!
            </p>
            <br>
            <a class="button button-filled" href="{{ route('products-catalog') }}">Shop Now</a>
        </div>
    </section>

@endsection
