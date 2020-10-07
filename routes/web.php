<?php

use App\Order;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index')->name('home');

// Shop
Route::resource('shop', 'ShopController')->only(['index', 'show']);

// Cart
Route::resource('cart', 'CartController')->only(['index', 'store', 'destroy']);
Route::patch('cart/{product}', 'CartController@update')->name('cart.update');

// Wishlist
Route::resource('wishlist', 'WishlistController')->only(['index', 'store', 'destroy']);

// Checkout
Route::resource('checkout', 'CheckoutController')->only(['index', 'store']);
Route::post('checkout/verify', 'CheckoutController@verify')->name('checkout.verify');

// Login checkout
Route::name('loginToCheckout')->get('loginToCheckout', 'Auth\LoginController@loginToCheckout');

// Coupon
Route::post('coupon', 'CouponController@store')->name('coupon.store');
Route::delete('coupon', 'CouponController@destroy')->name('coupon.destroy');

Route::get('thankyou', function() {
    // Delete "user_id" cookie
    setcookie('user_id', '', time()-3600);

	// Delete "cart" cookie
    setcookie('cart', '', time()-60);

	return view('thankyou');
})->name('thankyou');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();
