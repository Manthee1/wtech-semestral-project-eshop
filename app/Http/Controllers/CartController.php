<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartController extends Controller
{

    public function getCartItemsCount()
    {
        $cart_items = $this->getCartItems();
        if ($cart_items->isEmpty())
            return 0;

        $totalQuantity = 0;
        foreach ($cart_items as $cart_item)
            $totalQuantity += $cart_item->quantity;
        return $totalQuantity;
    }

    public function getCartItemsTotal()
    {
        $cart_items = $this->getCartItems();
        if ($cart_items->isEmpty())
            return 0;

        $total = 0;
        foreach ($cart_items as $cart_item)
            $total += $cart_item->product->price * $cart_item->quantity;
        return $total;
    }

    public function getCookieCartItemsString()
    {
        if (!Cookie::has("cart_items"))
            return "";

        $itemsString = Cookie::get("cart_items");
        if (empty(trim($itemsString)))
            return "";

        return $itemsString;
    }


    public function mergeCartItemsWithDatabase()
    {
        $cookie_cart_items = $this->getCartItemsFromCookie();
        $db_cart_items = auth()->user()->cartItems;
        $db_cart_items = $db_cart_items->keyBy('product_id');
        $cookie_cart_items = $cookie_cart_items->keyBy('product_id');
        $merged_cart_items = $db_cart_items->merge($cookie_cart_items);
        foreach ($merged_cart_items as $cart_item) {
            $db_cart_item = $db_cart_items->get($cart_item->product_id);
            $cookie_cart_item = $cookie_cart_items->get($cart_item->product_id);
            if ($db_cart_item == null) {
                unset($cart_item->id);
                $cart_item->user_id = auth()->user()->id;
                $cart_item->save();
            } else if ($cookie_cart_item != null) {
                $db_cart_item->quantity += $cookie_cart_item->quantity;
                $db_cart_item->save();
            }
        }

        // Clear the cookie
        $this->saveCookieCartItems(new Collection());
    }
    public function saveCookieCartItemsString($itemsString)
    {
        Cookie::queue("cart_items", $itemsString, 60 * 24 * 7);
    }

    public function saveCookieCartItems(Collection $cart_items)
    {
        $itemsString = "";
        if ($cart_items != null) {
            foreach ($cart_items as $cart_item) {
                $itemsString .= $cart_item->product_id . ":" . $cart_item->quantity . "|";
            }
        }
        $this->saveCookieCartItemsString($itemsString);
    }

    public function getCartItemsFromCookie()
    {
        if (!Cookie::has("cart_items"))
            return new Collection();

        $itemsString = $this->getCookieCartItemsString();
        if (empty(trim($itemsString)))
            return new Collection();

        $cart_items = new Collection();
        $items = explode('|', $itemsString);

        // Remove the last element if it is empty
        if (empty(trim(end($items))))
            array_pop($items);
        foreach ($items as $key => $value) {
            $item = explode(':', $value);
            $product_id = $item[0];
            $quantity = $item[1];
            $cart_items[$product_id] = new CartItem(['product_id' => $product_id, 'quantity' => $quantity, 'id' => $product_id]);
        }
        return $cart_items;
    }

    public function getCartItems()
    {
        return (auth()->check()) ? CartItem::where("user_id", auth()->user()->id)->get() : $this->getCartItemsFromCookie();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the cart items of the signed in user
        $cart_items = $this->getCartItems();
        return view("frontend.cart", ['cart_items' => $cart_items]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function add(Product $product)
    {
        // If the user is signed in, add the product to the cart
        try {
            if (!auth()->check()) {
                $cart_items = $this->getCartItems();
                $cart_item = $cart_items->where('product_id', $product->id)->first();
                if ($cart_item == null)
                    $cart_items[$product->id] = new CartItem(['product_id' => $product->id, 'quantity' => 1, 'id' => $product->id]);
                else
                    $cart_item->quantity++;

                $this->saveCookieCartItems($cart_items);

                if (request()->wantsJson())
                    return response()->json(['message' => 'Cart item added'], 200);
                else
                    return redirect()->back();
            }

            $cart_item = auth()->user()->cartItems->where('product_id', $product->id)->first();
            if ($cart_item == null) $cart_item = CartItem::create(['product_id' => $product->id, 'quantity' => 1, 'user_id' => auth()->user()->id]);
            else {
                $cart_item->quantity++;
                $cart_item->save();
            }

            if (request()->wantsJson())
                return response()->json(['message' => 'Cart item added'], 200);
            else
                return redirect()->back();
        } catch (\Throwable $th) {
            if (request()->wantsJson())
                return response()->json(['message' => $th->getMessage()], 400);
            else
                return redirect()->back()->withErrors($th->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $cart_item_id = null)
    {

        // If it wants a json response
        try {
            // If quantity is not set, return an error
            if (!isset($request->quantity))
                throw new \Exception("Quantity not set");

            if (auth()->check()) {
                $cart_item = auth()->user()->cartItems->where('id', $cart_item_id)->first();
                if ($cart_item == null)
                    throw new \Exception("Cart item not found");

                if ($request->quantity == 0) {
                    $cart_item->delete();
                    throw new \Exception("Cart item deleted");
                }
                $cart_item->quantity = $request->quantity;
                $cart_item->save();
            } else {
                $cart_items = $this->getCartItems();
                if ($cart_items == null)
                    throw new \Exception("Cart items not found");
                if ($request->quantity == 0)
                    unset($cart_items[$cart_item_id]);
                else
                    $cart_items[$cart_item_id]->quantity = $request->quantity;
                $this->saveCookieCartItems($cart_items);
            }
        } catch (\Throwable $th) {
            if ($request->wantsJson())
                return response()->json(['message' => $th->getMessage()], 400);
            else
                return redirect()->back()->withErrors($th->getMessage());
        }
        if ($request->wantsJson())
            return response()->json(['message' => 'Cart item updated'], 200);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function remove(Product $product)
    {
        try {
            if (auth()->check()) {
                $cart_item = auth()->user()->cartItems->where('product_id', $product->id)->first();
                if ($cart_item != null) {
                    $cart_item->delete();
                }
            } else {
                $cart_items = $this->getCartItems();
                $itemsString = "";
                if ($cart_items != null) {
                    foreach ($cart_items as $cart_item) {
                        if ($cart_item->product_id != $product->id) {
                            $itemsString .= $cart_item->product_id . ":" . $cart_item->quantity . "|";
                        }
                    }
                }
                Cookie::queue("cart_items", $itemsString, 60 * 24 * 7);
            }
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Cart item removed'], 200);
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            if (request()->wantsJson()) {
                return response()->json(['message' => $th->getMessage()], 400);
            }
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function clear()
    {
        if (auth()->check()) {
            auth()->user()->cartItems->each->delete();
        } else {
            $this->saveCookieCartItems(new Collection());
        }
        return redirect()->back();
    }
}
