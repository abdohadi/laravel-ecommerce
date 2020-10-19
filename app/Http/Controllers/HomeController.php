<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('quantity', '>', 0)
                            ->where('featured', TRUE)
                            ->inRandomOrder()
                            ->take(8)
                            ->get();

        return view('home', ['products' => $products]);
    }
}
