@extends('frontend.layout')

@section('title', 'Account Settings')
@section('styles')
    <style>
        #account-settings {
            max-width: 1200px;
        }

        @media (max-width: 480px) {
            #account-settings {
                padding: 2rem;
            }

            #account-settings input {
                width: 100%;
            }

            #account-settings button {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')

    <section>
        <section id="account-settings" class="flex-container w-auto p-5">
            <h1 class="text-extralarge">Account Settings</h1>
            {{-- <form class="flex form-container mb-6 gap-5"> --}}
            <x-alert />
            {{ Form::open(['route' => 'account-settings.update', 'method' => 'PUT', 'class' => 'flex form-container mb-6 gap-5']) }}
            <div class="form-section">
                <h5 class="form-section-title">Basic</h5>
                <hr>
                <x-form.input class="flex-6" type="text" name="first_name" :value="auth()->user()->first_name" id="firstName" label="First name" maxlength="100" />
                <x-form.input class="flex-6" type="text" name="last_name" :value="auth()->user()->last_name" id="lastName" label="Last name" maxlength="100" />
                <x-form.input class="flex-6" type="tel" name="phone_number" :value="auth()->user()->phone_number" id="phone_umber" label="Phone Number" />
                <x-form.input class="flex-6" type="email" name="email" :value="auth()->user()->email" id="email" label="Email" maxlength="255" />
            </div>

            <div class="form-section">
                <h5 class="form-section-title">Password</h5>
                <hr>
                <x-form.input class="flex-4" type="password" name="current_password" id="currentPassword" label="Current password" />
                <x-form.input class="flex-4" type="password" name="new_password" id="newPassword" label="New password" minlength="8" />
                <x-form.input class="flex-4" type="password" name="repeat_new_password" id="repeatNewPassword" label="Repeat new password" minlength="8" />
            </div>

            <div class="form-section">
                <h5 class="form-section-title">Delivery</h5>
                <hr>
                <x-form.input class="flex-12" type="text" name="street_address" :value="auth()->user()->street_address" id="streetAddress" label="Street address" />
                <x-form.input class="flex-6" type="text" name="city" :value="auth()->user()->city" id="city" label="City" />
                <x-form.select class="flex-6" name="country" :value="auth()->user()->country" id="country" label="Country" :options="config('countries')" />
            </div>

            <div class="form-section">
                <h5 class="form-section-title">Payment</h5>
                <hr>
                <x-form.input class="flex-12" type="text" name="card_name" id="card_name" label="Name on card" />
                <x-form.input class="flex-5" type="text" name="card_number" id="card_number" label="Card number" maxlength="16" />
                <x-form.input class="flex-2" type="text" name="card_expiration_year" id="card_expiration_year" label="Expiration year" maxlength="2" />
                <x-form.input class="flex-2" type="text" name="card_expiration_month" id="card_expiration_month" label="Expiration month" maxlength="2" />
                <x-form.input class="flex-1" type="text" name="cvv" id="cvv" label="CVV" maxlength="3" />
            </div>
            <hr>
            <div class="flex flex-12 width-full">
                <button class="button button-filled ml-auto">Save</button>
            </div>
            {{ Form::close() }}
        </section>
    </section>

@endsection
