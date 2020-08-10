<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::inRandomOrder()->take(12)->get();

        return view('shop', ['products' => $products]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $mightAlsoLike = Product::where('slug', '!=', $slug)->mightAlsoLike()->get();

        $duplicates = Cart::search(function($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product->id;
        });

        $isAlreadyInCart = $duplicates->isNotEmpty() ? true : false;

        return view('product', [
            'product' => $product,
            'mightAlsoLike' => $mightAlsoLike,
            'isAlreadyInCart' => $isAlreadyInCart
        ]);
    }
}
