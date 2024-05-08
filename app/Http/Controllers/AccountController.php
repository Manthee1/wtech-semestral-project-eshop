<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class AccountController extends Controller
{

    public function orders()
    {
        $orders = auth()->user()->orders;
        return view('frontend.account.orders', ['orders' => $orders]);
    }

    public function order($tracking_number)
    {
        // If the user does not have an order with the provided tracking number, give them an error
        $order = Order::where('tracking_number', $tracking_number)->first();

        if (!$order) {
            abort(404);
        }

        if ($order->user_id != auth()->user()->id) {
            abort(403);
        }

        return view('frontend.account.order', ['order' => $order]);
    }

    public function settings()
    {
        return view('frontend.account.settings');
    }

    public function update(AccountUpdateRequest $request)
    {

        $data = $request->all();
        $userAuthenticatable = auth()->user();
        // Get user model calss instance
        $user = User::find($userAuthenticatable->id);

        if ($request->filled('new_password')) {
            // If the password is being updated then hash it and move it to password field
            $data['password'] = Hash::make($data['new_password']);
        }

        $user->update($data);
        return redirect()->route('account-settings')->with('success', 'Account updated successfully');
    }
}
