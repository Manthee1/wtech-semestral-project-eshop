<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // If user is admin
        if ($user->role == 'Admin') {
            return redirect()->route('products.index');
        }

        // If the user is a regular user and has stuff in his cart.
        // Automatically merge them with the database cart items.

        if ($user->role == 'User') {
            $cookie_cart_items = (new CartController)->getCartItemsFromCookie();
            if ($cookie_cart_items->isNotEmpty()) (new CartController)->mergeCartItemsWithDatabase();
        }
    }

    public function showLoginForm()
    {
        return view('auth.auth');
    }
}
