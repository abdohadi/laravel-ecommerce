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
        <div class="product-section-images">
            <div class="product-section-main-image">
                <img src="{{ $product->imgPath() }}" class="active" id="main_image" alt="product">
            </div>

            @if ($product->images)
                @if (count($images = json_decode($product->images)) > 1)
                    <div class="product-section-thumbnails">
                        <div class="product-section-thumbnail selected">
                            <img src="{{ $product->imgPath() }}" class="thumbnail" alt="product">
                        </div>

                        @foreach ($images as $image)
                            <div class="product-section-thumbnail">
                                <img src="{{ asset('images/' . $image) }}" class="thumbnail" alt="product">
                            </div>
                        @endforeach
                    </div>
                @endif
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

@section('extra-js')
    <script>
        
        (function() {
            let image = document.querySelector('#main_image');
            let thumbnails = document.querySelectorAll('.thumbnail');

            thumbnails.forEach((el) => {
                el.addEventListener('click', thumbnailClick);
            });

            function thumbnailClick(e) {
                document.querySelector('.product-section-thumbnail.selected').classList.remove('selected');
                this.parentElement.classList.add('selected');
                image.classList.remove('active');

                image.addEventListener('transitionend', () => {
                    image.src = this.src;
                    image.classList.add('active');
                });
            }
        }());

    </script>
@endsection