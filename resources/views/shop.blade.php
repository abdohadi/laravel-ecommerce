@extends('layout')

@section('title', 'Shop')

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="{{ route('home') }}">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <a href="{{ route('shop.index') }}">Shop</a>
            @if (request()->has('category'))
                <i class="fa fa-chevron-right breadcrumb-separator"></i>
                <span>{{ request()->category }}</span>
            @endif
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="products-section container">
        <div class="sidebar">
            @if (! request()->has('category'))
                <h3>By Category</h3>
                <ul class="categories-list">
                    @foreach ($categories as $category)
                        <li><a href="{{ route('shop.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            @else
                <h3>Sort by Price</h3>
                <div class="sort-by-links">
                    <div class="{{ request()->sort == 'high_low' ? 'active' : ''}}">
                        @if (request()->sort == 'high_low')
                            <span>High to Low</span>
                        @else
                            <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'high_low']) }}">High to Low</a>
                        @endif
                    </div>
                    <div class="{{ request()->sort == 'low_high' ? 'active' : ''}}">
                        @if (request()->sort == 'low_high')
                            <span>Low to High</span>
                        @else
                            <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'low_high']) }}">Low to High</a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- <h3>By Price</h3>
            <ul>
                <li><a href="#">$0 - $700</a></li>
                <li><a href="#">$700 - $2500</a></li>
                <li><a href="#">$2500+</a></li>
            </ul> --}}
        </div> <!-- end sidebar -->
        <div>
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
