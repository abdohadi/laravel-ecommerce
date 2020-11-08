@extends('layout')

@section('title', 'Checkout')

@section('extra-css')

@endsection

@section('content')

    <div class="container">

        <h1 class="checkout-heading stylish-heading">Checkout</h1>

        {{-- Validation Errors --}}
        @include('partials.errors')

        @foreach ($warnings as $warning)
            @if ($warning)
                <div class="validation-warning-msg">{!! $warning !!}</div>
            @endif
        @endforeach

        @if ($productsAreNoLongerAvailable)
            <div class="validation-error-msg">{{ $productsAreNoLongerAvailable }}</div>
        @endif

        <div class="checkout-section">
            <div>
                <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                    <h2>Billing Details</h2>

                    @csrf

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required="">
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required="">
                    </div>

                    <div class="form-group">
                        <label for="billing_address">Address</label>
                        <input type="text" class="form-control" id="billing_address" name="billing_address" value="{{ old('billing_address') }}" required="">
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select class="form-control" id="country" name="country" required="">
                                <option value="">Select Country...</option>
                                @foreach (countries() as $country)
                                    <option value="{{ $country['val2'] }}" {{ old('country') == $country['val2'] ? 'selected' : '' }}>{{ $country['val0'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required="">
                        </div>
                    </div> <!-- end half-form -->

                    <div class="half-form">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required="">
                        </div>
                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required="">
                        </div>
                    </div> <!-- end half-form -->

                    <div class="spacer"></div>

                    <h2>Shipping Details</h2>

                    <div class="form-group">
                        <label for="address_shipping">Address</label>
                        <input type="text" class="form-control" id="address_shipping" name="address_shipping" value="{{ old('address_shipping') }}" required="">
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="country_shipping">Country</label>
                            <select class="form-control" id="country_shipping" name="country_shipping" required="">
                                <option value="">Select Country...</option>
                                @foreach (countries() as $country)
                                    <option value="{{ $country['val2'] }}" {{ old('country_shipping') == $country['val2'] ? 'selected' : '' }}>{{ $country['val0'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city_shipping">City</label>
                            <input type="text" class="form-control" id="city_shipping" name="city_shipping" value="{{ old('city_shipping') }}" required="">
                        </div>
                    </div> <!-- end half-form -->

                    <div class="half-form">
                        <div class="form-group">
                            <label for="state_shipping">State</label>
                            <input type="text" class="form-control" id="state_shipping" name="state_shipping" value="{{ old('state_shipping') }}" required="">
                        </div>
                        <div class="form-group">
                            <label for="postal_code_shipping">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code_shipping" name="postal_code_shipping" value="{{ old('postal_code_shipping') }}" required="">
                        </div>
                    </div> <!-- end half-form -->

                    <div class="spacer"></div>

                    <h2>Card Details</h2>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="cc_first_name">First Name on Card</label>
                            <input type="text" class="form-control" id="cc_first_name" name="cc_first_name" value="{{ old('cc_first_name') }}" required="">
                        </div>
                        <div class="form-group">
                            <label for="cc_last_name">Last Name on Card</label>
                            <input type="text" class="form-control" id="cc_last_name" name="cc_last_name" value="{{ old('cc_last_name') }}" required="">
                        </div>
                    </div> <!-- end half-form -->

                    <div class="form-group">
                        <label for="cc_phone_number">Card Phone Number</label>
                        <input type="text" class="form-control" id="cc_phone_number" name="cc_phone_number" value="{{ old('cc_phone_number') }}" required="">
                    </div>
                    
                    <div class="spacer"></div>

                    <button type="submit" class="button-primary full-width" id="checkout-submit-btn">Continue</button>

                </form><!-- end form -->
            </div>

            <div class="checkout-table-container">
                <h2>Your Order</h2>

                <div class="checkout-table">
                    @foreach (Cart::content() as $item)
                        <div class="checkout-table-row">
                            <div class="checkout-table-row-left">
                                <img src="{{ $item->model->imgPath() }}" alt="{{ $item->model->name }}" class="checkout-table-img">
                                <div class="checkout-item-details">
                                    <div class="checkout-table-item">{{ $item->model->name }}</div>
                                    <div class="checkout-table-description">{{ $item->model->details }}</div>
                                    <div class="checkout-table-price">{{ $item->model->presentPrice() }}</div>
                                </div>
                            </div> <!-- end checkout-table -->

                            <div class="checkout-table-row-right">
                                <div class="checkout-table-quantity">{{ $item->qty }}</div>
                            </div>
                        </div> <!-- end checkout-table-row -->
                    @endforeach

                </div> <!-- end checkout-table -->

                <div class="checkout-totals">
                    <div class="checkout-totals-left">
                        Subtotal <br>
                        Tax({{ config('cart.tax') }}%) <br>
                        <div class="hr"></div>
                        New Subtotal <br>
                        @if ($discount)
                            Discount ({{ $discountPercent ? $discountPercent.'%' : presentPrice($discount) }}) 
                            <form class="remove-coupon-form" action="{{ route('coupon.destroy') }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="button-icon">Remove</button>
                            </form>
                            <br>
                        @endif
                        <span class="checkout-totals-total">Total</span>
                    </div>

                    <div class="checkout-totals-right">
                        {{ presentPrice($subtotal) }} <br>
                        {{ presentPrice($tax) }} <br>
                        <div class="hr"></div>
                        {{ presentPrice($newSubtotal) }} <br>
                        @if ($discount)
                            -{{ presentPrice($discount) }} <br>
                        @endif
                        <span class="checkout-totals-total">{{ presentPrice($total) }}</span>
                    </div>
                </div> <!-- end checkout-totals -->
            </div>
        </div> <!-- end checkout-section -->
    </div>

@endsection


@section('extra-js')
    <script>

        // Make checkout submit button disabled after submitting the form
        window.onload = function() {
            document.querySelector('#checkout-form').addEventListener('submit', () => {
                document.querySelector('#checkout-submit-btn').disabled = true;
            });
        }

    </script>
@endsection