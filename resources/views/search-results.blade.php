@extends('layout')

@section('title', 'Shop')

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <div>
                <a href="{{ route('home') }}">Home</a>
                <i class="fa fa-chevron-right breadcrumb-separator"></i>
                <span>Search</span>
            </div>

            @include('partials.search-form')
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="container">
        <div class="search-section">
            <h2>Search Results</h2>

            <h4>{{ count($products) }} result(s) for '{{ request()->input('query') }}'</h4>

            <div class="search-products">
                @foreach ($products as $product)
                    <div class="search-product">
                        <div>
                            <a href="{{ route('shop.show', $product) }}"><img src="{{ $product->imgPath() }}" alt="{{ $product->name }}"></a>
                        </div>

                        <div class="product-info">
                            <h3 class="product-name"><a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a></h3>
                            <h4 class="product-details">{{ $product->details }}</h4>
                            <h4 class="product-price">{{ $product->presentPrice() }}</h4>
                            <p class="product-description">{{ Str::limit($product->description, 80) }}</p>
                        </div>
                    </div>
                @endforeach

                {{ $products->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

@endsection
