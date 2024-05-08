@extends('backend.layout')

@section('title', 'Products')
@section('styles')
    <style>
        .table-container {
            max-width: 1200px;
        }

        table {
            font-size: 1.5rem;
        }
    </style>
@endsection

@section('content')

    <section class="flex-container width-full table-container admin-view gap-2">
        <x-alert />
        <div class="flex flex-row gap-2">
            <a href="{{ route('products.create') }}" class="button button-filled button-small">
                <ion-icon name="add-outline"></ion-icon>
                <span>Add Product</span>
            </a>
            <form action="{{ route('products.index') }}" method="GET">
                <input class="input-small" type="text" name="search" placeholder="Search Products" value="{{ request('search') }}">
                <button type="submit" class="button button-icon-clear button-small">
                    <ion-icon name="search-outline" role="img"></ion-icon>
                </button>
            </form>
        </div>
        <br>
        <table class="compact">
            <thead>
                <tr>
                    <th width="50px">Id</th>
                    <th>Product Name</th>
                    <th width="100px">Price</th>
                    <th width="100px">Stock</th>
                    <th width="50px">Active</th>
                    <th width="50px"></th>
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
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button-icon-clear button-small">
                                    <ion-icon name="trash-outline" role="img"></ion-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </tbody>
        </table>
        <x-pagination :paginator="$products" />

    </section>

@endsection
