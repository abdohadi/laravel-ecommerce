@extends('layout')

@section('title', 'Shop')

@section('extra-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/docs-searchbar.js/dist/cdn/docs-searchbar.min.css" />
@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <div>
                <a href="{{ route('home') }}">Home</a>
                <i class="fa fa-chevron-right breadcrumb-separator"></i>
                @if (request()->has('category'))
                    <a href="{{ route('shop.index') }}">Shop</a>
                    <i class="fa fa-chevron-right breadcrumb-separator"></i>
                    <span class="visited">{{ request()->category }}</span>
                @else
                    <span class="visited">Shop</span>
                @endif
            </div>

            @include('partials.search-form')
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="products-section container">
        <div class="sidebar">
            <div class="inner-sidebar">
                <h3>Filter By Category</h3>
                <ul class="categories-list">
                    @foreach ($categories as $category)
                        @if (request()->category == $category->slug)
                            <li class="active">{{ $category->name }}</li>
                        @else
                            <li>
                                <a href="{{ route('shop.index', ['category' => $category->slug, 'sort' => request()->sort]) }}">{{ $category->name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                <h3>Sort by Price</h3>
                <ul class="sort-by-price-list">
                    <li class="{{ request()->sort == 'high_low' ? 'active' : ''}}">
                        @if (request()->sort == 'high_low')
                            <span>High to Low</span>
                        @else
                            <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'high_low']) }}">High to Low</a>
                        @endif
                    </li>
                    <li class="{{ request()->sort == 'low_high' ? 'active' : ''}}">
                        @if (request()->sort == 'low_high')
                            <span>Low to High</span>
                        @else
                            <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'low_high']) }}">Low to High</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div> <!-- end sidebar -->

        <div class="products-section-all">
            <h1 class="stylish-heading">{{ $categoryName }}</h1>

            <div class="products text-center">
                @forelse ($products as $product)
                    <div class="product">
                        <a href="{{ route('shop.show', $product->id) }}"><img src="{{ $product->imgPath() }}" alt="product"></a>
                        <a href="{{ route('shop.show', $product->id) }}"><div class="product-name">{{ $product->name }}</div></a>
                        <div class="product-price">{{ $product->presentPrice() }}</div>
                    </div>
                @empty
                    <div class="empty">No items found</div>
                @endforelse
            </div> <!-- end products -->

            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>


@endsection

@section('extra-js')
    @include('partials.js.search-section')
@endsection

