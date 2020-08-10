<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Facades\Cart;

class Product extends Model
{
    public function presentPrice()
    {
    	return '$' . $this->price;
    }

    public function isInWishlist()
    {
    	$duplicates = Cart::instance('wishlist')->search(function($cartItem, $rowId) {
            return $cartItem->id == $this->id;
        });

        return $duplicates->isNotEmpty() ? true : false;
    }

    public function scopeMightAlsoLike($query)
    {
    	return $query->inRandomOrder()->take(4);
    }
}
