@extends('backend.layout')

@section('title', 'Dashboard')
@section('styles')
    <style>



    </style>
@endsection

@section('content')

    <div class="container flex-container">
        <h1>Dashboard</h1>
        <div class="flex flex-row flex-wrap gap-4">
            <div class="card flex-3">
                <div class="card-header">
                    Latest Orders
                </div>
                <div class="card-body">
                    <ul class="no-bullet flex flex-column gap-4">
                        @foreach ($latestOrders as $order)
                            <li class="card">
                                <h6 class="m-0">Order #{{ $order->id }} - {{ $order->tracking_number }}</h6>
                                <small>Order Date: {{ $order->created_at->format('d/m/Y') }}</small>
                                <br>
                                <small>Order Status: {{ $order->status }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="flex flex-column flex-left gap-4">
                <div class="card">
                    <div class="card-header">
                        Total Orders
                    </div>
                    <div class="card-body">
                        <p>Total number of orders: {{ $totalOrders }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Earnings
                    </div>
                    <div class="card-body">
                        <p>Last year: ${{ $earnings['lastYear'] }}</p>
                        <p>Last month: ${{ $earnings['lastMonth'] }}</p>
                        <p>Last week: ${{ $earnings['lastWeek'] }}</p>
                        <p>Last day: ${{ $earnings['lastDay'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
