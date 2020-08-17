<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CouponController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();

        if (! $coupon) {
            return back()->with('error-message', 'Invalid coupon code. Please try again.');
        }

        session()->put('coupon', [
            'code' => $request->code,
            'discount' => $coupon->discount(Cart::subtotal(2, '.', ''))
        ]);

        return back()->with('success-message', 'Coupon has been applied!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        session()->forget('coupon');

        return back()->with('success-message', 'Coupon has been removed successfully!');
    }
}
