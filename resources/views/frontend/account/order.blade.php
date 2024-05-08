@extends('frontend.layout')

@section('title', 'Order - ' . $order->tracking_number)
@section('styles')
    <style>

    </style>
@endsection

@section('content')

    {{-- Just make a table of all the orders --}}
    <section id="account-order" class="container flex-container w-auto p-5">
        <h1 class="text-extralarge">Order #{{ $order->id }} - {{ $order->tracking_number }}</h1>
        <table class="width-full">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->order_items as $item)
                    <tr>
                        <td>{{ $item->product->getName() }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product->getFormattedPrice() }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td>{{ $order->getFormattedTotal() }}</td>
                </tr>
            </tfoot>
        </table>

    </section>

@endsection
