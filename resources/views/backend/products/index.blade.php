@extends('backend.layout')

@section('title', 'Products')

@section('content')

    <section class="flex-container width-full table-container admin-view gap-2 p-5">
        <x-alert />
        <div class="flex flex-row gap-2">
            <a href="{{ route('products.create') }}" class="button button-filled button-small">
                <ion-icon name="add-outline"></ion-icon>
                <span>Add Product</span>
            </a>
            <form action="{{ route('products.index') }}" method="GET">
                <input hidden type="text" name="order" value="{{ request('order') }}">
                <input hidden type="text" name="sort" value="{{ request('sort') }}">
                <input class="input-small" type="text" name="search" placeholder="Search Products" value="{{ request('search') }}">
                <button type="submit" class="button button-icon-clear button-small">
                    <ion-icon name="search-outline" role="img"></ion-icon>
                </button>
            </form>
        </div>
        <br>
        <table class="compact text-medium">
            <thead>
                @php
                    $columns = [
                        [
                            'name' => 'id',
                            'title' => 'Id',
                            'width' => '50px',
                            'order' => true,
                        ],
                        [
                            'name' => 'name',
                            'title' => 'Product Name',
                            'order' => true,
                        ],
                        [
                            'name' => 'price',
                            'title' => 'Price',
                            'width' => '100px',
                            'order' => true,
                        ],
                        [
                            'name' => 'stock',
                            'title' => 'Stock',
                            'width' => '100px',
                            'order' => true,
                        ],
                        [
                            'name' => 'active',
                            'title' => 'Active',
                            'width' => '50px',
                            'order' => true,
                        ],
                        [
                            'name' => 'actions',
                            'title' => '',
                            'width' => '50px',
                            'order' => false,
                        ],
                    ];

                    $order = request('order', 'id');
                    $sort = request('sort', 'asc');

                    $requestParams = request()->except(['order', 'sort']);

                    $orderIcon = $sort === 'asc' ? 'chevron-up' : 'chevron-down';

                @endphp
                <tr>
                    @foreach ($columns as $column)
                        <th style="width: {{ $column['width'] ?? 'auto' }}">
                            @if ($column['order'])
                                <a href="{{ route('products.index', array_merge($requestParams, ['order' => $column['name'], 'sort' => $order === $column['name'] && $sort === 'asc' ? 'desc' : 'asc'])) }}">
                                    {{ $column['title'] }}
                                    @if ($order === $column['name'])
                                        <ion-icon name="{{ $orderIcon }}"></ion-icon>
                                    @endif
                                </a>
                            @else
                                {{ $column['title'] }}
                            @endif
                        </th>
                    @endforeach
                </tr>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php
                        // Get the product as an instance of the Product model
                        $product = App\Models\Product::find($product->id);
                    @endphp
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td><a class="font-bold" href="{{ route('products.edit', $product->id) }}">{{ $product->getName() }}</a></td>
                        <td>{{ $product->getFormattedPrice() }}</td>
                        <td>{{ $product->stock }}</td>
                        <td><ion-icon name="{{ $product->active ? 'checkmark' : 'close' }}"></ion-icon></td>

                        <td>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button-icon-clear button-small">
                                    <ion-icon name="trash-outline" role="img"></ion-icon>
                                </button>
                            </form>
                        </td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </tbody>
        </table>
        <x-pagination :paginator="$products" />

    </section>

@endsection
