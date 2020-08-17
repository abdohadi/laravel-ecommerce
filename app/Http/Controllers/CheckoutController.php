<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subtotal = doubleval(Cart::subtotal(2, '.', ''));
        $discount = session()->get('coupon')['discount'] ?? 0;
        $newSubtotal = $subtotal - $discount;
        $newTax = (config('cart.tax') / 100) * $newSubtotal;
        $newTotal = $newSubtotal + $newTax;

        return view('checkout')->with([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'newSubtotal' => $newSubtotal,
            'newTax' => $newTax,
            'newTotal' => $newTotal
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
}
