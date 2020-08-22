@extends('layout')

@section('title', $product->name)

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="/">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <a href="{{ route('shop.index') }}">Shop</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>{{ $product->name }}</span>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="product-section container">
        <div>
            <div class="product-section-image">
                <img src="{{ $product->imgPath() }}" alt="product">
            </div>

            @if (count($images = json_decode($product->images)) > 1)
                <div>
                    @for ($i = 1; $i < count($images); $i++)
                        <img src="{{ asset('images/' . $images[$i]) }}">
                    @endfor
                </div>
            @endif
        </div>
        <div class="product-section-information">
            <div class="product-section-first-row">
                <h2 class="product-section-title">{{ $product->name }}</h2>
                
                @if ($product->isInWishlist())
                    {{-- Remove from wishlist from --}}
                    <form class="heart-item-form" action="{{ route('wishlist.destroy', $wishlistRowId) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="button-icon" title="Remove from Wishlist">
                            <i class="fas fa-heart solid-heart"></i>
                        </button>
                    </form>
                @else
                    {{-- Add to wishlist form --}}
                    <form class="heart-item-form" action="{{ route('wishlist.store') }}" method="post">
                        @csrf

                        <input type="hidden" name="row_id" value="{{ $wishlistRowId }}">
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <button type="submit" class="button-icon" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>
                @endif
            </div>

            <div class="product-section-subtitle">{{ $product->details }}</div>
            
            <div class="product-section-price">{{ $product->presentPrice() }}</div>

            <p>{!! $product->description !!}</p>

            <p>&nbsp;</p>

            @if ($product->isInCart())
                <div class="empty">Item is already in Cart</div>
            @else 
                <form action="{{ route('cart.store') }}" method="post">
                    @csrf

                    @if ($rowId = $product->getCartRowId('wishlist'))
                        <input type="hidden" name="row_id" value="{{ $rowId }}">
                    @endif
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <button class="button button-plain" type="submit">Add to Cart</button>
                </form>
            @endif
        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')


@endsection
