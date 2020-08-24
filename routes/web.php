<?php

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

// Coupon
Route::post('coupon', 'CouponController@store')->name('coupon.store');
Route::delete('coupon', 'CouponController@destroy')->name('coupon.destroy');


Route::view('/thankyou', 'thankyou');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

