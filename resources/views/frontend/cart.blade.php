@extends('frontend.layout')

@section('title', 'Cart')
@section('description', 'Your cart items')

@section('content')

    @include('frontend.partials.breadcrumbs', [
        'breadcrumbs' => [['link' => route('home'), 'name' => 'Home'], ['link' => route('cart'), 'name' => 'Cart']],
    ])


    <section id="cart" class="flex flex-container p-5 my-5 gap-5 ">
        <h1 class="text-extralarge m-0 mr-auto width-full">Cart</h1>

        @if ($cart_items->isEmpty())
            <div class="flex-container py-6 my-6">
                <span class="text-center width-full font-bold text-large py-6">Cart is empty</span>
                <a href="{{ route('products-catalog') }}" class="button button-filled">Continue shopping</a>
            </div>
        @else
            <ul class="cart-container no-bullet flex flex-column gap-5">
                @foreach ($cart_items as $cart_item)
                    <li class="cart-item flex flex-row flex-left flex-nowrap gap-5 p-5 position-relative" data-price="{{ $cart_item->product->price }}">
                        @if ($cart_item->product->images->isNotEmpty())
                            <img src="{{ $cart_item->product->images[0]->getUrl() }}">
                        @endif
                        <div class="text-container flex flex-column flex-3 flex-top-left">
                            <h5 class="cart-item-name"><a href="{{ route('product-detail', $cart_item->product->getSlug()) }}">{{ $cart_item->product->getName() }}</a></h5>
                            <span class="cart-item-price mb-3">{{ $cart_item->product->getFormattedPrice() }}</span>
                            <p class="cart-item-description">{{ $cart_item->product->description }}</p>
                        </div>
                        <div class="cart-item-quantity-container flex flex-center flex-auto flex-nowrap m-auto flex-center">
                            {{ html()->form()->open(['route' => ['cart.update', $cart_item->id], 'method' => 'PUT']) }}
                            <input type="hidden" name="quantity" value="{{ $cart_item->quantity - 1 }}">
                            <button type="submit" class="button-icon-clear">
                                <ion-icon name="chevron-back-circle"></ion-icon>
                            </button>
                            {{ html()->form()->close() }}
                            <span class="cart-item-quantity">{{ $cart_item->quantity }}</span>
                            {{ html()->form()->open(['route' => ['cart.update', $cart_item->id], 'method' => 'PUT']) }}
                            <input type="hidden" name="quantity" value="{{ $cart_item->quantity + 1 }}">
                            <button type="submit" class="button-icon-clear">
                                <ion-icon name="chevron-forward-circle"></ion-icon>
                            </button>
                            {{ html()->form()->close() }}
                        </div>
                        <span class="cart-item-price-total font-bold text-large text-right">{{ formatPrice($cart_item->product->price * $cart_item->quantity) }}</span>
                        {{ html()->form('DELETE', route('cart.remove', $cart_item->id))->class('cart-item-remove')->open() }}
                        <button type="submit" class="button-icon-clear close-icon">
                            <ion-icon name="close-outline" role="img" class="icon-large"></ion-icon>
                        </button>
                        {{ html()->form()->close() }}
                    </li>
                @endforeach
            </ul>
        @endif
        @if ($cart_items->isNotEmpty())
            <div class="flex flex-row flex-left gap-3">
                <span class="text-subtext text-medium m-auto flex-auto">*shipping is free</span>
                <h6 class="m-auto">TOTAL</h6>
                <h5 class="m-auto font-bold cart-total">
                    {{ formatPrice(
                        $cart_items->sum(function ($cart_item) {
                            return $cart_item->product->price * $cart_item->quantity;
                        }),
                    ) }}
                </h5>
            </div>
            <hr>
            <div class="buttons-container flex flex-row width-full gap-3">
                <a class="button button-outlined" href="{{ route('products-catalog') }}">Continue shopping</a>
                <a class="button button-filled" href="{{ route('checkout') }}">Proceed to checkout</a>
            </div>
        @endif
    </section>
@endsection

@section('scripts')
    <script>
        function recalulateAllPrices() {
            let cartItemCount = 0;
            document.querySelectorAll('.cart-item').forEach((cartItem) => {
                let basePrice = cartItem.dataset.price;
                let quantity = cartItem.querySelector('.cart-item-quantity').textContent;
                cartItemCount += Number(quantity);
                cartItem.querySelector('.cart-item-price-total').textContent = formatPrice(basePrice * quantity);
            });

            // Recalculate total
            let total = document.querySelector('.cart-total');
            let cartItems = document.querySelectorAll('.cart-item');
            let totalPrice = Array.from(cartItems).reduce((sum, cartItem) => {
                let basePrice = cartItem.dataset.price;
                let quantity = cartItem.querySelector('.cart-item-quantity').textContent;
                return sum + (basePrice * quantity);
            }, 0);
            total.textContent = formatPrice(totalPrice);

            if (cartItemCount === 0) {
                location.reload();
            }

            // Update ccarrt count
            setCartCount(cartItemCount);


        }

        function removeCartItem(cartItem) {
            cartItem.remove();
            recalulateAllPrices();
        }

        function updateCartItemQuantity(cartItem, quantity) {
            cartItem.querySelector('.cart-item-quantity').textContent = quantity;
            // Update quantity for hidden inputs
            let inputs = cartItem.querySelectorAll('input[name="quantity"]');
            console.log(inputs);
            inputs[0].value = quantity - 1;
            inputs[1].value = quantity + 1;
            recalulateAllPrices();
        }

        // Hijack update form submission and submit via AJAX
        document.querySelectorAll('.cart-item-quantity-container form').forEach((form) => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                const formData = new FormData(form);

                // If the new quantity is 0, give a confirmation dialog
                if (Number(formData.get('quantity')) === 0) {
                    let confimed = await confirmModal('Are you sure you want to remove this item from the cart?')
                    if (!confimed)
                        return;
                }

                try {
                    const response = await fetch(form.action, {
                        method: formData.get('_method'),
                        body: JSON.stringify(Object.fromEntries(formData)),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });
                    const data = await response.json();
                    let quantity = Number(formData.get('quantity'));
                    if (quantity === 0)
                        removeCartItem(form.closest('.cart-item'));
                    else
                        updateCartItemQuantity(form.closest('.cart-item'), quantity);
                } catch (error) {
                    toast('Failed to update cart item quantity', 'error');
                    console.error(error);
                }
            });
        });


        document.querySelectorAll('form.cart-item-remove').forEach((form) => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                const formData = new FormData(form);
                let confimed = await confirmModal('Are you sure you want to remove this item from the cart?')
                if (!confimed)
                    return;

                console.log('Removing item from cart');
                try {
                    const response = await fetch(form.action, {
                        method: formData.get('_method'),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });
                    const data = await response.json();
                    removeCartItem(form.closest('.cart-item'));
                } catch (error) {
                    toast('Failed to remove item from cart', 'error');
                    console.error(error);
                }
            });
        });
    </script>
@endsection
