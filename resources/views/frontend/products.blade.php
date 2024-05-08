@extends('frontend.layout')

@section('title', 'Products')

@section('content')

    @include('frontend.partials.breadcrumbs', [
        'breadcrumbs' => [['link' => route('home'), 'name' => 'Home'], ['link' => route('products-catalog'), 'name' => 'Products']],
    ])
    <div id="products" class="flex flex-row flex-nowrap flex-top width-full">
        <section class="filter-container p-5 m-4">
            <!-- header -->
            <div class="flex flex-row gap-4 filter-header">
                <span class="text-extralarge font-bold">Filter</span>
                <button class="button-icon-clear" onclick="document.querySelector('#products').classList.toggle('filter-container-expanded')">
                    <ion-icon name="close"></ion-icon>
                </button>
            </div>
            <br>
            <form class="flex flex-column gap-5" action="{{ route('products-catalog') }}" method="GET">
                <input type="hidden" name="page" value="1">
                <div class="search-section width-full">
                    <label for="search" class="sr-only p-2">SEARCH</label>
                    <input class="width-full" type="text" placeholder="Search Products" id="search" name="search" value="{{ request()->input('search') }}">
                </div>

                <div class="input-section">
                    <label for="price">PRICE</label>
                    <div class="flex flex-row flex-left flex-nowrap gap-4">
                        <input class="input-clear flex-auto" type="number" id="price-from" name="price-from" placeholder="From" value="{{ request()->input('price-from') }}">
                        <input class="input-clear flex-auto" type="number" id="price-to" name="price-to" placeholder="To" value="{{ request()->input('price-to') }}">
                    </div>
                </div>
                <div class="input-section">
                    <label for="efficiency-from">EFFICIENCY</label>
                    <div class="flex flex-row flex-left flex-nowrap gap-4">
                        <input class="input-clear flex-auto" type="number" id="efficiency-from" name="efficiency-from" placeholder="From" value="{{ request()->input('efficiency-from') }}">
                        <input class="input-clear flex-auto" type="number" id="efficiency-to" name="efficiency-to" placeholder="To" value="{{ request()->input('efficiency-to') }}">
                    </div>
                </div>

                <x-filter-checkbox-section :items="$makes" name="makes"></x-filter-checkbox-section>
                <x-filter-checkbox-section :items="$models" name="models"></x-filter-checkbox-section>

                <div class="input-section">
                    <label for="year-from">YEAR</label>
                    <div class="flex flex-row flex-left flex-nowrap gap-4">
                        <input class="input-clear flex-auto" type="number" id="year-from" name="year-from" placeholder="From" value="{{ request()->input('year-from') }}">
                        <input class="input-clear flex-auto" type="number" id="year-to" name="year-to" placeholder="To" value="{{ request()->input('year-to') }}">
                    </div>
                </div>

                <x-filter-checkbox-section :items="$drivetrains" name="drivetrains"></x-filter-checkbox-section>
                <x-filter-checkbox-section :items="$engineTypes" name="engineTypes"></x-filter-checkbox-section>
                <x-filter-checkbox-section :items="$bodyTypes" name="bodyTypes"></x-filter-checkbox-section>
                <input class="button-filled" type="submit" value="Apply">

            </form>

        </section>
        <section class="products-container flex flex-column gap-5 p-5 my-4 width-full">
            <div class="product-options flex flex-column gap-5">
                <div class="product-header">
                    <h1 class="text-extralarge flex m-0">
                        Products
                        <button class="button-filled py-1 px-2 gap-2" onclick="document.querySelector('#products').classList.toggle('filter-container-expanded')">

                            <ion-icon class="m-auto" name="options"></ion-icon>
                            <span class="m-auto">Filter</span>
                        </button>

                    </h1>
                </div>
                @php
                    $requestParams = request()->except('order', 'sort');
                    $requestParams['page'] = 1;
                @endphp
                <div class="flex flex-row flex-left gap-4">
                    <label class="m-0" for="order">Order By</label>
                    <br>
                    @php
                        $order = request()->input('order');
                        $sort = request()->input('sort');
                        $orderings = [
                            [
                                'name' => 'Most Expensive',
                                'value' => 'price',
                                'sort' => 'asc',
                                'icon' => 'cash',
                            ],
                            [
                                'name' => 'Least Expensive',
                                'value' => 'price',
                                'sort' => 'desc',
                                'icon' => 'cash',
                            ],
                            [
                                'name' => 'Most Efficient',
                                'value' => 'efficiency',
                                'sort' => 'asc',
                                'icon' => 'star',
                            ],
                            [
                                'name' => 'Least Efficient',
                                'value' => 'efficiency',
                                'sort' => 'desc',
                                'icon' => 'star',
                            ],
                        ];
                    @endphp
                    <ul class="flex flex-row gap-2 no-bullet m-0 hidden-md">
                        @foreach ($orderings as $ordering)
                            <li class="flex flex-left gap-2">
                                <ion-icon name="{{ $ordering['icon'] }}"></ion-icon>
                                <a href="{{ route('products-catalog', array_merge($requestParams, ['order' => $ordering['value'], 'sort' => $ordering['sort']])) }}"
                                    class="{{ request()->input('order') == $ordering['value'] && request()->input('sort') == $ordering['sort'] ? 'font-bold' : '' }}">
                                    {{ $ordering['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <select class="visible-md select-small" id="order-select" onchange="window.location.href = this.value">
                        @foreach ($orderings as $ordering)
                            <option value="{{ route('products-catalog', array_merge($requestParams, ['order' => $ordering['value'], 'sort' => $ordering['sort']])) }}"
                                {{ request()->input('order') == $ordering['value'] && request()->input('sort') == $ordering['sort'] ? 'selected' : '' }}>
                                {{ $ordering['name'] }}
                            </option>
                        @endforeach
                    </select>

                    <br>
                </div>

            </div>
            <!-- filter expand button -->

            @if ($products->isEmpty())
                <span class="text-center width-full font-bold text-large p-6">No products found</span>
            @else
                <ul class="product-list-container no-bullet flex flex-row flex-wrap gap-5 flex-top-left">
                    @foreach ($products as $product)
                        @php
                            // Get the product as an instance of the Product model
                            $product = App\Models\Product::find($product->id);
                        @endphp
                        <li class="product-card button">
                            <a href="{{ route('product-detail', $product->getSlug()) }}">
                                <img src="{{ $product->getMainImageUrl() }}" alt="{{ $product->name }}">
                                <div class="product-card-content">
                                    <h5 class="product-card-title m-0">{{ $product->getName() }}</h5>
                                    <ol class="product-quick-specs no-bullet flex flex-wrap flex-top-left gap-2">
                                        @php
                                            $tags = [$product->make->name, $product->model->name, $product->year, $product->drivetrain?->name, $product->body_type?->name, $product->engine_type?->name];
                                        @endphp
                                        @foreach ($tags as $tag)
                                            @if ($tag)
                                                <li class="tag-bubble">{{ $tag }}</li>
                                            @endif
                                        @endforeach
                                    </ol>
                                    </ol>
                                    <br>
                                    <p class="product-card-description">{{ $product->description }}</p>
                                </div>
                                <span class="product-card-price text-center text-large">{{ $product->getFormattedPrice() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <!-- pagination -->
                <x-pagination :paginator="$products" />

            @endif
        </section>
    </div>

@endsection


@section('scripts')
    <script>
        //    Make sure the from fields are less than the to fields
        const priceFrom = document.querySelector('#price-from');
        const priceTo = document.querySelector('#price-to');
        const efficiencyFrom = document.querySelector('#efficiency-from');
        const efficiencyTo = document.querySelector('#efficiency-to');
    </script>

    <script>
        const searchInputs = document.querySelectorAll('.search-input');

        searchInputs.forEach((searchInput) => {

            const parent = searchInput.closest('.checkbox-section');

            searchInput.addEventListener('input', function() {
                const search = searchInput.value.toLowerCase();
                const checkboxSection = searchInput.closest('.checkbox-section');
                const checkboxes = checkboxSection.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach((checkbox) => {
                    const name = checkbox.getAttribute('data-name');
                    let match = name.toLowerCase().includes(search)
                    checkbox.disabled = !match;
                    checkbox.parentElement.style.display = match ? 'flex' : 'none';
                });
            });

            // Show or hide the show more and show less buttons depending on the number of checkboxes
            const checkboxContainer = parent.querySelector('.checkbox-container');
            const showMore = parent.querySelector('.show-more');
            const showLess = parent.querySelector('.show-less');
            const showMoreCount = 5;
            // Get a checkbox height
            const checkboxHeight = checkboxContainer.children[0]?.offsetHeight;

            checkboxContainer.style.maxHeight = `${checkboxHeight * showMoreCount}px`;
            showMore.style.display = checkboxContainer.childElementCount > showMoreCount ? 'block' : 'none';
            showLess.style.display = 'none';

            showMore.addEventListener('click', function() {
                checkboxContainer.style.maxHeight = 'none';
                showMore.style.display = 'none';
                showLess.style.display = 'block';
            });

            showLess.addEventListener('click', function() {
                checkboxContainer.style.maxHeight = `${checkboxHeight * showMoreCount}px`;
                showMore.style.display = 'block';
                showLess.style.display = 'none';
            });
        });


        const form = document.querySelector('#products .filter-container form');
        form.addEventListener('change', e => {
            fetchProducts();
        });
        form.addEventListener('input', e => {
            fetchProducts();
        });


        // This is so that the sticky filter container follows your bottom, and not your top.
        function updateFilterContainerTop() {
            const filterContainer = document.querySelector('.filter-container');
            const navbar = document.querySelector('nav');
            filterContainer.style.top = `${document.body.clientHeight - filterContainer.clientHeight -20 }px`;
        }

        window.addEventListener('load', () => {
            updateFilterContainerTop();
        });

        window.addEventListener('resize', () => {
            updateFilterContainerTop();
        });

        // Any html update on filter container will trigger this function
        const observer = new MutationObserver((mutations) => {
            updateFilterContainerTop();
        });

        observer.observe(document.querySelector('.filter-container'), {
            attributes: true,
            childList: true,
            subtree: true,
        });





        // let timeoutId;

        // function fetchProducts() {
        //     clearTimeout(timeoutId);
        //     timeoutId = setTimeout(() => {
        //         form.submit();
        //         // const form = document.querySelector('#products .filter-container form');
        //         // const formData = new FormData(form);
        //         // const url = '{{ route('products-filter') }}';
        //         // const params = new URLSearchParams(formData).toString();
        //         // const productsContainer = document.querySelector('.products-container');
        //         // fetch(`${url}?${params}`)
        //         //     .then(response => response.text())
        //         //     .then(html => {

        //         //     });
        //     }, 1000);
        // }
    </script>

@endsection
