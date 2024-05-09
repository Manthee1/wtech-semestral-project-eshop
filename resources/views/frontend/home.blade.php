@extends('frontend.layout')

@section('title', 'Home')
@section('description', 'Ride with Confidence and embark on a journey of unparalleled luxury and performance. Our handpicked selection of cars exudes power, elegance, and sheer excellence.')
@section('keywords', 'Luxury cars, Performance vehicles, Prestige automobiles, Exotic cars, High-end vehicles, Automotive excellence, Premium car dealership, Exclusive car collection, Luxury car showroom, Elite car selection')

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
