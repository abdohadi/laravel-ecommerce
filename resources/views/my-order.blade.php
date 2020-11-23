@extends('layout')

@section('title', 'My Orders')

@section('extra-css')
@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <div>
                <a href="/">Home</a>
                <i class="fa fa-chevron-right breadcrumb-separator"></i>
                <a href="{{ route('orders.index') }}">My Orders</a>
                <i class="fa fa-chevron-right breadcrumb-separator"></i>
                <span class="visited">Orders Details</span>
            </div>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="products-section container profile-section">
        <div class="sidebar">
            <div class="inner-sidebar">
                <ul>
                    @if (request()->url() == route('profile.edit'))
                        <li class="active">My Profile</li>
                    @else
                        <li><a href="{{ route('profile.edit') }}">My Profile</a></li>
                    @endif

                    @if (request()->url() == route('orders.index'))
                        <li class="active">My Orders</li>
                    @else
                        <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="products-section-all">
            <h1 class="stylish-heading">Order Details</h1>

            <div class="order-box">
                <div class="shipping-address-box">
                    <h3>Shipping Address</h3>

                    <div>
                        {{ $order->shipping_address }} <br>
                        {{ $order->shipping_city }}, 
                        {{ $order->shipping_state }}, 
                        {{ $order->shipping_country }}
                    </div>
                </div>

                <div>
                    <h3>Payment Method</h3>

                    <div class="payment-method">
                        <img src="/images/visa.png" alt="Visa"> <span>**** 3485</span>
                    </div>
                </div>

                <div class="order-summary-box">
                    <h3>Order Summary</h3>

                    <div class="order-summary">
                        <div>
                            <div>Item(s) Subtotal:</div>

                            <div>{{ presentPrice($order->subtotal) }}</div>
                        </div>

                        <div>
                            <div>Tax:</div>
                            
                            <div>{{ presentPrice($order->tax) }}</div>
                        </div>

                        <div>
                            <div>Total after tax:</div>
                            
                            <div>{{ presentPrice($order->subtotal + $order->tax) }}</div>
                        </div>

                        @if ($order->discount)
                            <div>
                                <div>Discount</div>
                                
                                <div>{{ presentPrice($order->discount) }}</div>
                            </div>
                        @endif

                        <div class="grand-total">
                            <div><strong>Grand Total:</strong></div>
                            
                            <div><strong>{{ presentPrice($order->total) }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="products-box">
                <h3>Order Items</h3>

                <div class="order-products">
                    @foreach ($products as $product)
                        <div class="product-details">
                            <img src="{{ $product->imgPath() }}" alt="{{ $product->name }}">

                            <div>
                                <div><a class="product-name" href="{{ route('shop.show', $product) }}">{{ $product->name }}</a></div>
                                <div class="product-price">{{ $product->presentPrice() }}</div>
                                <div>Quantity:  {{ $product->pivot->quantity }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div> <!-- end product-section -->

@endsection

@section('extra-js')
@endsection

