<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Controllers\CartController;


class OrderController extends Controller
{
    /**
     * Create a new order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $cart_items = (new CartController)->getCartItems();
        if ($cart_items->isEmpty()) {
            return redirect()->route('cart');
        }

        $data = $request->all();
        $order = Order::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'total' => (new CartController)->getCartItemsTotal(),
            'street_address' => $data['street_address'],
            'city' => $data['city'],
            'country' => $data['country'],
            'tracking_number' => uniqid('R'),
            'status' => $data['payment_type'] == 'card' ? 'Awaiting Payment' : 'Awaiting Fulfillment',
            'user_id' => auth()->user()?->id,
        ]);
        foreach ($cart_items as $cart_item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart_item->product_id,
                'quantity' => $cart_item->quantity,
                'unit_price' => $cart_item->product->price,
            ]);
        }

        (new CartController)->clear();
        return redirect()->route('order.confirmation', ['tracking_number' => $order->tracking_number]);
    }

    public function checkout()
    {
        $cart_items = (new CartController)->getCartItems();
        if ($cart_items->isEmpty())
            return redirect()->route('cart');

        return view('frontend.checkout', ['cart_items' => $cart_items]);
    }

    public function confirmation($tracking_number)
    {
        $order = Order::where('tracking_number', $tracking_number)->first();
        return view('frontend.order.confirmation', ['order' => $order]);
    }
}
