<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
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
        if (request()->category) {
            $targetCategory = Category::where('slug', request()->category)->firstOrFail();
            $products = $targetCategory->products();
            $categoryName = $targetCategory->name;
            $categories = '';

            if (request()->sort == 'high_low') {
                $products = $products->orderBy('price', 'desc');
            } else if (request()->sort == 'low_high') {
                $products = $products->orderBy('price');
            } else {
                $products = $products->inRandomOrder();
            }
        } else {
            $products = Product::where('featured', TRUE)->inRandomOrder();
            $categories = Category::all();
            $categoryName = 'Featured';
        }

        $products = $products->paginate(12);

        return view('shop', compact(['products', 'categories', 'categoryName']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $mightAlsoLike = Product::where('id', '!=', $id)->mightAlsoLike()->get();

        return view('product', [
            'product' => $product,
            'mightAlsoLike' => $mightAlsoLike
        ]);
    }
}
