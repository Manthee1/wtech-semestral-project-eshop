@extends('frontend.layout')

@section('title', $product->getName())
@section('styles')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.0/js/splide.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.0/css/splide.min.css" integrity="sha512-KhFXpe+VJEu5HYbJyKQs9VvwGB+jQepqb4ZnlhUF/jQGxYJcjdxOTf6cr445hOc791FFLs18DKVpfrQnONOB1g==" crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <style>
        #product-detail {
            min-height: calc(100vh - var(--navbar-height));

        }

        .product-info-container {
            padding: 5rem 100px;
            max-width: 600px;
            min-width: 500px;
            background-color: var(--color-base-background);
        }

        .splide__slide img {
            width: 100%;
            height: 450px;
            object-fit: contain;
        }

        .splide__pagination__page.is-active {
            background-color: var(--color-main-regular);
        }

        @media (max-width: 800px) {

            /* .product-images-container{} */
            #product-images-container {
                padding: 0px;
            }


            .splide__slide {
                display: flex;
                flex-flow: column nowrap;
                justify-content: center;
                align-content: center;
            }

            .splide__slide img {
                width: 80%;
                height: auto;
                margin: auto;
            }


            #product-detail {
                flex-flow: column;
            }


            .product-info-container {
                padding: 2rem;
                min-width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    {{-- Breadcrumbs --}}
    @include('frontend.partials.breadcrumbs', [
        'breadcrumbs' => [
            ['link' => route('products-catalog'), 'name' => 'Products'],
            ['link' => route('products-catalog') . '?makes[]=' . $product->make->id, 'name' => $product->make->name],
            ['link' => route('products-catalog') . '?makes[]=' . $product->make->id . '&models[]=' . $product->model->id, 'name' => $product->model->name],
            ['link' => route('product-detail', $product->getSlug()), 'name' => $product->getName()],
        ],
    ])
    {{-- Product Detail --}}
    <section id="product-detail" class="flex flex-row flex-nowrap flex-center width-full height-full">
        <div id="product-images-container" class="flex-auto flex flex-center p-5">
            <div class="splide width-full flex-auto">
                <div class="splide__track">
                    <ul class="splide__list no-bullet">
                        {{-- <li class="splide__slide"><img src="img/car1.jpg" alt="product image" class="product-image">
                        </li> --}}
                        @foreach ($product->images as $image)
                            <li class="splide__slide"><img src="{{ $image->getUrl() }}" alt="product image" class="product-image"></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-info-container flex flex-column flex-top-left mx-5 p-5 gap-5">
            <div>
                <h1 class="product-title m-0 font-regular">{{ $product->make->name }} {{ $product->model->name }}</h1>
                <span>{{ $product->year }} {{ $product->body_type }}</span>
            </div>
            <h5 class="product-price font-bold">{{ $product->getFormattedPrice() }}</h5>
            <div class="product-description">
                <label class="product-description-label font-bold">D E S C R I P T I O N</label>
                <p class="product-description ml-3">{{ $product->description }}</p>
            </div>
            <div class="product-details width-full">
                <label class="product-details-label font-bold">D E T A I L S</label>
                <table class="product-details-table compact width-full text-medium">
                    <tr>
                        <td>Make</td>
                        <td>{{ $product->make->name }}</td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td>{{ $product->model->name }}</td>
                    </tr>
                    <tr>
                        <td>Year</td>
                        <td>{{ $product->year }}</td>
                    </tr>
                    <tr>
                        <td>Body Type</td>
                        <td>{{ $product->body_type?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Horse Power</td>
                        <td>{{ $product->horse_power ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Engine Type</td>
                        <td>{{ $product->engine_type?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Dimensions</td>
                        <td>{{ $product->width ?? 'N/A' }} x {{ $product->height ?? 'N/A' }} x {{ $product->length ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Efficiency</td>
                        <td>{{ $product->efficiency ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Drivetrain</td>
                        <td>{{ $product->drivetrain?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Passenger Capacity</td>
                        <td>{{ $product->passenger_capacity ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            {{ Form::open(['route' => ['cart.add', $product->id], 'method' => 'POST', 'class' => 'width-full']) }}
            <button class="button button-filled width-full">Add to Cart</button>
            {{ Form::close() }}
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const image_count = document.querySelectorAll('.splide__slide').length;
            new Splide('.splide', {
                type: image_count > 1 ? 'loop' : 'fade',
                perPage: 1,
                perMove: 1,
                gap: '1rem',
                pagination: image_count > 1 ? true : false,
            }).mount();
        });

        // Hijack the form submission and add the product to the cart using fetch
        document.addEventListener('submit', async function(e) {
            if (e.target.tagName === 'FORM') {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();
                    toast('Product added to cart', 'success');
                    // Update the cart count
                    let currentCartCount = parseInt(document.querySelector('.cart-count').textContent);
                    setCartCount(currentCartCount + 1);


                } catch (error) {
                    toast('Failed to add product to cart', 'error');
                    console.error('Error:', error);
                }
            }
        });
    </script>


@endsection
