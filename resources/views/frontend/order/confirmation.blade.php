@extends('frontend.layout')

@section('title', 'Order #' . $order->tracking_number . ' - Confirmation')

@section('content')
    <section class="flex-container width-full height-100vh order-summary m-auto">
        <div class="order-summary-container flex flex-column gap-3 flex-center p-5 m-auto">
            <h1 class="text-extralarge">Thank you for your purchase!</h1>
            <h2 class="text-large">Your tracking number is: <b>{{ $order->tracking_number }}</b></h2>
            <p class="text-center text-intermed">Check your email for your order confirmation, tracking number and
                other important
                information.</p>
            <div class="flex flex-column gap-3">
                @if (auth()->user())
                    <a href="{{ route('account-orders') }}" class="button button-outlined">See Orders</a>
                @endif
                <a href="{{ route('products-catalog') }}" class="button button-filled">Continue Shopping</a>
            </div>
        </div>
    </section>
@endsection
