@extends('frontend.layout')

@section('title', Route::currentRouteName() === 'login' ? 'Login' : 'Register')
@section('styles')
    <style>
        .auth-form-wrapper {
            height: calc(100vh - var(--navbar-height));
        }

        @media (max-width: 480px) {
            .auth-form-wrapper {
                height: auto;
            }

            .auth-form-wrapper .form-container {
                margin: 0 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <section class="flex-container width-full w-auto auth-form-wrapper">
        <div class="m-auto">
            <div class="flex flex-row gap-2 mb-3">
                <h5 id="auth-form-title" class="m-0 mr-auto font-bold">Login</h5>
                <a id="registerSwitchButton" class="tab-button my-auto" href="{{ route('register') }}">Register</a>
                <a id="loginSwitchButton" class="tab-button my-auto active" href="{{ route('login') }}">Login</a>
            </div>
            <hr>
            <br>

            {{ html()->form('POST', route('login'))->class('flex flex-column gap-4')->id('loginForm')->style(Route::currentRouteName() === 'login' ? '' : 'display: none')->open() }}
            <x-form.input type="email" name="email" id="email" label="Email" required />
            <x-form.input type="password" name="password" id="password" label="Password" required />
            <button class="button button-filled" type="submit">Login</button>
            {{ html()->form()->close() }}

            {{ html()->form('POST', route('register'))->class('flex flex-column gap-4')->id('registerForm')->style(Route::currentRouteName() === 'register' ? '' : 'display: none')->open() }}
            <x-form.input type="text" name="first_name" id="first_name" label="First Name" required />
            <x-form.input type="text" name="last_name" id="last_name" label="Last Name" required />
            <x-form.input type="email" name="email" id="email" label="Email" required />
            <x-form.input type="password" name="password" id="password" label="Password" required />
            <x-form.input type="password" name="password_confirmation" id="password_confirmation" label="Confirm Password" required />
            <button class="button button-filled" type="submit">Register</button>
            {{ html()->form()->close() }}
            <br>
            {{-- show register errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        const formContainerEl = document.querySelector('.auth-form-wrapper');
        const loginFormEl = document.getElementById('loginForm');
        const registerFormEl = document.getElementById('registerForm');

        function switchTo(type) {
            if (type === 'login') {
                loginFormEl.style.display = 'flex';
                registerFormEl.style.display = 'none';

                formContainerEl.querySelector('#auth-form-title').innerText = 'Login';
                formContainerEl.querySelector('#registerSwitchButton').classList.remove('active');
                formContainerEl.querySelector('#loginSwitchButton').classList.add('active');
                window.history.replaceState({}, '', "{{ route('login') }}");
            } else {
                loginFormEl.style.display = 'none';
                registerFormEl.style.display = 'flex';

                formContainerEl.querySelector('#auth-form-title').innerText = 'Register';
                formContainerEl.querySelector('#loginSwitchButton').classList.remove('active');
                formContainerEl.querySelector('#registerSwitchButton').classList.add('active');
                window.history.replaceState({}, '', "{{ route('register') }}");
            }
        }
        window.addEventListener('load', function() {
            // Depending on whether the url is login or register, switch to the appropriate form
            // Also cheekily change the url visually so it is apparent which form is active
            if (window.location.href.includes('register'))
                switchTo('register');
            else
                switchTo('login');

        });
    </script>
@endsection
