<section id="breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumb">
        {{-- <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active"><a href="/products.html">Products</a></li>
        <li class="breadcrumb-item active">Acura</li>
        <li class="breadcrumb-item active" aria-current="page">MDX 2011</li> --}}
        @foreach ($breadcrumbs as $breadcrumb)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" {{ $loop->last ? 'aria-current="page"' : '' }}><a href="{{ $breadcrumb['link'] }}">{{ $breadcrumb['name'] }}</a></li>
        @endforeach

    </ol>
</section>
