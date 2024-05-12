<nav>
    <a class="logo-container flex gap-2 flex-nowrap" href="{{ route('home') }}">
        <img class="logo" height="32px" src="/logo.png" alt="rideout logo">
        <span>rideout</span>
    </a>
    <div class="nav-links flex-auto gap-4">
        <a href="{{ route('home') }}" class="{{ Route::is('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('products-catalog') }}" class="{{ Route::is('products-catalog') ? 'active' : '' }}">Products</a>
    </div>
    </div>

    <form class="search-container width-full" action="{{ route('products-catalog') }}" method="GET">
        <label for="navSearch" class="sr-only p-2"><ion-icon name="search"></ion-icon></label>
        <input class="width-full" type="text" placeholder="Search Products" id="navSearch" name="search">
        <button type="submit" class="button-icon-clear"><ion-icon-icon name="search"></ion-icon-icon></button>
    </form>

    @if (auth()->check())
        <div class="account-container flex-auto flex-right">
            <div class="dropdown">
                <button class="button button-icon-clear button-small flex flex-nowrap gap-2 dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <ion-icon name="person"></ion-icon>
                    <span class="text-medium m-auto">{{ auth()->user()->first_name }}</span>
                    <ion-icon name="chevron-down"></ion-icon>
                </button>
                <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                    {{-- Admin pannal if user is admin --}}
                    @if (auth()->user() && auth()->user()->isAdmin())
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <ion-icon name="settings"></ion-icon>
                                <span>Admin Panel</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="dropdown-item" href="{{ route('account-settings') }}">
                            <ion-icon name="settings"></ion-icon>
                            <span>Account Settings</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('account-orders') }}">
                            <ion-icon name="receipt"></ion-icon>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="javascript:void(0)" onclick="this.closest('form').submit()" class="dropdown-item">
                                <ion-icon name="log-out"></ion-icon>
                                <span>Logout</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    @else
        <div class="account-container flex-auto flex-right">
            <a href="{{ route('login') }}" class="button button-icon-clear button-small flex flex-nowrap gap-2">
                <ion-icon name="person"></ion-icon>
                <span class="text-medium">Login</span>
            </a>
        </div>
    @endif
    <div class="cart-container">
        <a href="{{ route('cart') }}" class="button button-icon-clear button-small">
            <ion-icon name="cart"></ion-icon>
            @php
                $cartItemsCount = (new App\Http\Controllers\CartController())->getCartItemsCount();
            @endphp
            <span class="cart-count" style="{{ $cartItemsCount === 0 ? 'display:none' : '' }}">{{ $cartItemsCount }}</span>
        </a>
    </div>
    <div class="menu-container">
        <button class="button-icon-clear navbar-toggle" onclick="document.querySelector('nav').classList.toggle('open')">
            <ion-icon class="open-icon" name="menu"></ion-icon>
            <ion-icon class="close-icon" name="close"></ion-icon>
        </button>
    </div>
</nav>
