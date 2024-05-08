<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        rideout - @yield('title')
    </title>
    <script type="module" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="Martin Dinja">

    <link rel="icon" href="favicon.ico" type="image/x-icon">

    @vite(['resources/sass/main.sass', 'resources/js/main.js'], 'build')

    @yield('styles')
</head>

<body>
    @include('frontend.layout.navbar')

    <main>
        @yield('content')
    </main>

    @include('frontend.layout.footer')
    @include('frontend.partials.confirm-modal')

    <div id="toasts-container" class="toasts-container"></div>
</body>


@yield('scripts')



</html>
