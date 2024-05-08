<?php

// Laravel admin routes
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Order;



Route::group(['middleware' => ['admin']], function () {
    Route::get('/', function () {

        $latestOrders = Order::latest()->take(10)->get();
        $totalOrders = Order::count();
        $earnings = [
            'lastYear' => Order::whereYear('created_at', now()->year)->sum('total'),
            'lastMonth' => Order::whereMonth('created_at', now()->month)->sum('total'),
            'lastWeek' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total'),
            'lastDay' =>  Order::whereDate('created_at', now()->today())->sum('total'),
        ];

        return view('backend.dashboard', [
            'latestOrders' => $latestOrders,
            'totalOrders' => $totalOrders,
            'earnings' => $earnings,
        ]);
    })->name('admin.dashboard');

    Route::resource('products', ProductController::class);

    Route::get('/attributes', function () {
        return view('backend.attributes');
    })->name('admin.attributes');
});
