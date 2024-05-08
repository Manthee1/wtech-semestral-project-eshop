<?php

use Illuminate\Support\Facades\Route;

// Models
use App\Models\Product;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // Return a view with a list of products taking in account the search query, page, order and sort direction if they are specified
    return view('frontend.home');
})->name('home');

Route::get('/products', function () {
    $viewVariables = (new ProductController)->filter(request());
    return view('frontend.products', $viewVariables);
})->name('products-catalog');

Route::get('/products-filter', function () {
    $viewVariables = (new ProductController)->filter(request());
    return response()->json($viewVariables);
})->name('products-filter');


Route::get('/products/{slug}', function ($slug) {
    //    Slug is: {make}-{model}-{year}-{body_type}-{id}
    $slugParts = explode('-', $slug);
    $id = array_pop($slugParts);
    $product = Product::find($id);
    return view('frontend.product-detail', [
        'product' => $product,
    ]);
})->name('product-detail');


Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{cart_item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');


Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/confirmation/{tracking_number}', [OrderController::class, 'confirmation'])->name('order.confirmation');



// account-settings
Route::middleware(['auth'])->group(function () {
    Route::get('/account-settings', [AccountController::class, 'settings'])->name('account-settings');
    Route::put('/account-settings', [AccountController::class, 'update'])->name('account-settings.update');
    Route::get('/account-orders', [AccountController::class, 'orders'])->name('account-orders');
    Route::get('/account-orders/{tracking_number}', [AccountController::class, 'order'])->name('account-order');
});


// Include the login, register routes. NOt done in the admin.php file sinbce it has the /admin prefix and we wan't the login and register routes to be at the root level
Auth::routes(['verify' => false]);
