@extends('backend.layout')

@section('title', 'Create Product')
@section('styles')
    <style>

    </style>
@endsection

@section('content')

    {{ html()->form('POST', route('products.store'))->acceptsFiles()->open() }}
    <section id="product-form" class="flex-container w-auto p-5">
        <h1 class="text-extralarge">Add Product</h1>
        @include('backend.products._form')

        <button class="button button-filled ml-auto mt-5" type="submit">Save</button>
    </section>

    {{ html()->form()->close() }}

@endsection
