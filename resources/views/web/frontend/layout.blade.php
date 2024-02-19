<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title')
    </title>
    @vite(['resources/sass/app.sass', 'resources/js/app.js'])

    @yield('head')
</head>

<body>
    @include('web.frontend.layout.navbar')

    <main>
        @yield('content')
    </main>

    @include('web.frontend.layout.footer')

</body>

</html>
