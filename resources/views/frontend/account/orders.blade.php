@extends('frontend.layout')

@section('title', 'Orders')
@section('styles')
    <style>

    </style>
@endsection

@section('content')

    {{-- Just make a table of all the orders --}}
    <section id="account-orders" class="flex-container w-auto p-5">
        <h1 class="text-extralarge">Orders</h1>
        @if ($orders->isEmpty())
            <p>You have no orders.</p>
        @else
            <table class="width-full">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Tracking Number</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><b><a href="{{ route('account-order', $order->tracking_number) }}">{{ $order->tracking_number }}</a></b></td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->getFormattedTotal() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

@endsection
