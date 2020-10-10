@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <div>
                <a href="{{ route('home') }}">Home</a>
                <i class="fa fa-chevron-right breadcrumb-separator"></i>
                <span>Shopping Cart</span>
            </div>

            @include('partials.search-form')
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="cart-section container">
        <div>
            @if (count($errors))
                <ul class="validation-error-msg">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            @if (Cart::count())
                <h2>{{ Cart::count() }} item(s) in Shopping Cart</h2>

                <div class="cart-table">
                    @foreach (Cart::content() as $item)
                        <div class="cart-table-row">
                            <div class="cart-table-row-left">
                                <a href="{{ route('shop.show', $item->model->id) }}">
                                    <img src="{{ $item->model->imgPath() }}" alt="{{ $item->model->name }}" class="cart-table-img">
                                </a>
                                <div class="cart-item-details">
                                    <div class="cart-table-item">
                                        <a href="{{ route('shop.show', $item->model->id) }}">{{ $item->model->name }}</a>
                                    </div>
                                    <div class="cart-table-description">{{ $item->model->details }}</div>
                                </div>
                            </div>
                            <div class="cart-table-row-right">
                                <div class="quantity-section">
                                    {{-- Update quantity form --}}
                                    <form class="quantity-form" action="{{ route('cart.update', $item->model) }}" method="post">
                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden" name="row_id" value="{{ $item->rowId }}">
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            class="quantity" 
                                            value="{{ $item->qty }}" min="1" max="{{ $item->model->quantity }}">
                                    </form>
                                </div>

                                <div>{{ presentPrice($item->subtotal()) }}</div>

                                <div class="cart-table-actions">
                                    {{-- Add to wishlist form --}}
                                    <form class="heart-item-form" action="{{ route('wishlist.store') }}" method="post">
                                        @csrf

                                        <input type="hidden" name="row_id" value="{{ $item->rowId }}">
                                        <input type="hidden" name="id" value="{{ $item->model->id }}">
                                        <input type="hidden" name="name" value="{{ $item->model->name }}">
                                        <input type="hidden" name="price" value="{{ $item->model->price }}">
                                        <button type="submit" class="button-icon" title="Add to Wishlist">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </form>

                                    {{-- Remove form cart form --}}
                                    <form class="remove-item-form" action="{{ route('cart.destroy', $item->rowId) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="button-red" title="Remove from Cart">x</button>
                                    </form> 
                                </div>
                            </div>
                        </div> <!-- end cart-table-row -->
                    @endforeach
                </div> <!-- end cart-table -->

                <div class="cart-totals">
                    <div class="cart-totals-left">
                        Shipping is free because we’re awesome like that. Also because that’s additional stuff I don’t feel like figuring out :).
                    </div>

                    <div class="cart-totals-right">
                        <div>
                            Subtotal <br>
                            Tax <span class="small-text">({{ config('cart.tax') }}%)</span> <br>
                            <span class="cart-totals-total">Total</span>
                        </div>
                        <div class="cart-totals-subtotal">
                            {{ presentPrice(Cart::subtotal()) }} <br>
                            {{ presentPrice(Cart::tax()) }} <br>
                            <span class="cart-totals-total">{{ presentPrice(Cart::total()) }}</span>
                        </div>
                    </div>
                </div> <!-- end cart-totals -->

                <div class="cart-buttons">
                    <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                    <a href="{{ auth()->user() ? route('checkout.index') : route('loginToCheckout') }}" class="button-primary">Proceed to Checkout</a>
                </div>
            @else 
                <div class="empty">No items in Cart!</div>
                <div class="spacer"></div>
                <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                <div class="spacer"></div>
            @endif
        </div>

    </div> <!-- end cart-section -->

    @include('partials.might-like')

@endsection


@section('extra-js')

    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>

        (function() {
            var quantityInputs = document.querySelectorAll('.quantity');
            var events = ['change', 'keydown'];

            events.forEach(function(event) {
                quantityInputs.forEach(function(input) {
                    input.addEventListener(event, function(e) {
                        var form = e.target.form;

                        if (! Boolean(form.getElementsByClassName('quantity-submit')[0])) {
                            var div = document.createElement('div');
                            div.innerHTML = '<button type="submit" class="button-blue quantity-submit">Save</button>';

                            form.append(div);
                        }
                    });
                });
            }); 
        }());

        function showSubmitBtn() {
            console.log(e);
        }

    </script>

@endsection