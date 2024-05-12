<nav class="admin-navbar">
    <div class="logo-container flex-left flex flex-column gap-0 flex-nowrap">
        <a class="flex flex-center flex-nowrap gap-2" href="/">
            <ion-icon name="car-sport-outline"></ion-icon>
            <span>rideout</span>
        </a>
        <a href="{{ route('admin.dashboard') }}" class="m-0 ml-auto">
            <p class="">Admin</p>
        </a>

    </div>
    <ul class="no-bullet flex-row width-full gap-2">
        {{--
            <li><a class="button text-large " href="#"><ion-icon name="home"></ion-icon> Dashboard</a></li>
        <li><a class="button text-large active" href="{{ url('/admin.html') }}"><ion-icon name="cube"></ion-icon> Products</a></li>
        <li><a class="button text-large" href="{{ url('/attributes.html') }}"><ion-icon name="cube"></ion-icon> Attributes</a></li>
         --}}
        <li><a href="{{ route('admin.dashboard') }}" class="button text-large {{ Route::is('admin.dashboard') ? 'active' : '' }}"><ion-icon name="home"></ion-icon> Dashboard</a></li>
        <li><a href="{{ route('products.index') }}" class="button text-large {{ Route::is('products.index') ? 'active' : '' }}"><ion-icon name="cube"></ion-icon> Products</a></li>
        <li><a href="{{ route('admin.attributes') }}" class="button text-large {{ Route::is('admin.attributes') ? 'active' : '' }}"><ion-icon name="cube"></ion-icon> Attributes</a></li>


    </ul>
</nav>
