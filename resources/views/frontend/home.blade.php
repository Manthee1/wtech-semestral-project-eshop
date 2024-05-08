@extends('frontend.layout')

@section('title', 'Home')
@section('styles')
    <style>
        #banner {
            position: relative;
            height: calc(100vh - var(--navbar-height));
            display: flex;
            justify-content: left;
            align-items: center;
            color: #fff;
            text-align: left;
            padding: 0 10em;
        }

        .text-content {
            margin-top: -20rem;
        }

        .banner-image {
            position: absolute;
            width: 70vw;
            object-fit: contain;
            z-index: -1;
            right: 4rem;
            bottom: 5%;
        }

        @media (max-width: 700px) {
            #banner {
                padding: 0 2em;
                /* align-items: flex-start; */
            }

            .banner-image {
                width: 125%;
                left: 0;
                bottom: calc(13vh - 11vw);
            }
        }
    </style>
@endsection

@section('content')
    <section id="banner">
        <img class="banner-image" src="./img/banner-car-backgrond.png" alt="banner">
        <div class="text-content">
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
