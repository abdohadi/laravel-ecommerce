<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Facades\Cart;

class Product extends Model
{
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Order');
    }

    public function getPriceAttribute($value)
    {
        return round($value, 2);
    }

    public function scopeMightAlsoLike($query)
    {
    	return $query->inRandomOrder()->take(4);
    }

    public function presentPrice($price = null)
    {
        // If price is not comming from cart (subtotal)
        if (! $price) {   
        	$price = number_format($this->price, 2, '.', ',');
        }

        return '$' . $price;
    }

    public function imgPath() 
    {
        return asset('images/' . $this->main_image);
    }

    public function getCartRowId($instance)
    {
        $itemCollection = Cart::instance($instance)->search(function($cartItem, $rowId) {
            return $cartItem->id == $this->id;
        });

        return $itemCollection->first() ? $itemCollection->first()->rowId : NULL;
    }

    public function isInWishlist()
    {
        return $this->checkCartDuplicates('wishlist');
    }

    public function isInCart()
    {
        return $this->checkCartDuplicates('default');
    }

    protected function checkCartDuplicates($instance = 'default')
    {
        $duplicates = Cart::instance($instance)->search(function($cartItem, $rowId) {
            return $cartItem->id == $this->id;
        });

        return $duplicates->isNotEmpty() ? true : false;
    }
}
