@extends('frontend.layout')

@section('title', 'Checkout')
@section('styles')
    <style>
        #checkout {
            max-width: 1200px;
        }

        @media (max-width: 480px) {
            #checkout {
                padding: 2rem;
            }

            #checkout input {
                width: 100%;
            }

            #checkout button {
                width: 100%;
            }

            .buttons-container {
                flex-flow: column;
            }
        }
    </style>
@endsection

@section('content')
    <section id="checkout" class="flex-container w-auto p-5">
        <h1 class="text-extralarge">Checkout</h1>
        <x-alert />
        {{-- Show all old() values --}}
        {{-- @dump(old()) --}}
        {{ html()->form('POST', route('order.store'))->class('flex form-container mb-6 gap-5')->open() }}
        <div class="form-section">
            <h5 class="form-section-title">Summary</h5>
            {{-- SHow a quick summary of the product, quantity, and its price --}}
            <hr>
            <table class="width-full">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart_items as $cart_item)
                        <tr>
                            <td>{{ $cart_item->product->getName() }}</td>
                            <td>{{ $cart_item->quantity }}</td>
                            <td>{{ $cart_item->product->getFormattedPrice() }}</td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">Total</td>
                        <td>{{ formatPrice(
                            $cart_items->sum(function ($cart_item) {
                                return $cart_item->product->price * $cart_item->quantity;
                            }),
                        ) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>

        <div class="form-section">
            <h5 class="form-section-title">Delivery</h5>
            <hr>
            <x-form.input class="flex-6" type="text" name="first_name" id="first_name" label="First name" maxlength="100" required />>
            <x-form.input class="flex-6" type="text" name="last_name" id="last_name" label="Last name" maxlength="100" required />
            <x-form.input class="flex-6" type="tel" name="phone_number" id="phone_number" label="Phone Number" />
            <x-form.input class="flex-6" type="email" name="email" id="email" label="Email" maxlength="255" required />
            <x-form.input class="flex-12" type="text" name="street_address" id="street_address" label="Street address" maxlength="255" required />
            <x-form.input class="flex-4" type="text" name="city" id="city" label="City" maxlength="60" required />
            <x-form.select class="flex-4" name="country" id="country" label="Country" :options="config('countries')" required />
        </div>

        <div class="form-section">
            <div class="flex flex-row gap-2 mb-3 flex-auto">
                <h5 class="form-section-title flex-auto">Payment</h5>
                <x-form.input type="radio-tab" name="payment_type" value="card" id="cardSwitchButton" onclick="switchTo('card')" label="Card"></x-form.input>
                <x-form.input type="radio-tab" name="payment_type" value="bank-transfer" id="bankSwitchButton" onclick="switchTo('bank-transfer')" label="Bank Transfer"></x-form.input>
            </div>
            <hr>
            <p class="bank-transfer-info" style="display: none">Please check your email for bank transfer details after placing your
                order.</p>
            <x-form.input class="flex-12" type="text" name="card_name" id="card_name" label="Name on card" maxlength="201" required />
            <x-form.input class="flex-5" type="text" name="card_number" id="card_number" label="Card number" required maxlength="16" />
            <x-form.input class="flex-2" type="text" name="card_expiration_year" id="card_expiration_year" label="Expiration year" maxlength="2" required />
            <x-form.input class="flex-2" type="text" name="card_expiration_month" id="card_expiration_month" label="Expiration month" maxlength="2" required />
            <x-form.input class="flex-1" type="text" name="cvv" id="cvv" label="CVV" required maxlength="3" />
        </div>
        <hr>
        <div class="buttons-container flex flex-row width-full gap-3">
            <a class="button button-outlined" href="{{ route('cart') }}">Go back to cart</a>
            <button type="submit" class="button button-filled">Place order</button>
        </div>
        {{ html()->form()->close() }}

    </section>
@endsection

@section('scripts')
    <script>
        function switchTo(type) {
            document.querySelectorAll('input[name="payment_type"]').forEach(function(radio) {
                radio.checked = radio.value === type;
            });
            cardFields = ['card_name', 'card_number', 'card_expiration_year', 'card_expiration_month', 'cvv'];
            if (type === 'card') {
                document.querySelector('.bank-transfer-info').style.display = 'none';
                cardFields.forEach(function(field) {
                    document.getElementById(field).required = true;
                    document.getElementById(field).closest('.input-wrapper').style.display = 'block';
                });
            } else {
                document.querySelector('.bank-transfer-info').style.display = 'block';
                cardFields.forEach(function(field) {
                    document.getElementById(field).required = false;
                    document.getElementById(field).closest('.input-wrapper').style.display = 'none';
                });
            }
        }

        document.querySelectorAll('input[name="payment_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                switchTo(this.value);
            });
        });

        window.addEventListener('load', function() {
            let selectedPaymentTypeInput = document.querySelector('input[name="payment_type"]:checked');
            switchTo(selectedPaymentTypeInput ? selectedPaymentTypeInput.value : 'card');
        });
    </script>
    </script>
@endsection
