@extends('backend.layout')

@section('title', 'Edit' . $product->getName())
@section('styles')
    <style>

    </style>
@endsection

@section('content')

    {{ Form::open(['route' => ['products.update', $product->id], 'method' => 'PUT', 'files' => true]) }}

    <section id="product-form" class="flex-container w-auto p-5">
        <h1 class="text-extralarge">Add Product</h1>
        @include('backend.products._form')

        <button class="button button-filled ml-auto mt-5" type="submit">Save</button>
    </section>

    {{ Form::close() }}



@endsection