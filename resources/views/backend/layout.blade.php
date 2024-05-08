<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        rideout Admin - @yield('title')
    </title>
    <script type="module" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="favicon.ico" type="image/x-icon">


    @vite(['resources/sass/main.sass', 'resources/js/main.js'])

    @yield('styles')
</head>

<body>
    @include('backend.layout.navbar')

    <main class="admin">
        @yield('content')
    </main>

</body>


@yield('scripts')



</html>
