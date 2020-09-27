<?php

namespace App\Http\Controllers;

use App\Paytabs\Paytabs;
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
        $newTotal = $this->getNumbers()->get('newTotal');
        $muchPrice = $newTotal > 5000 ? 'The total price of products should be between 0.27 and 5000.00 USD' : null;

        return view('checkout')->with([
            'subtotal' => $this->getNumbers()->get('subtotal'),
            'tax' => $this->getNumbers()->get('tax'),
            'total' => $this->getNumbers()->get('total'),
            'discount' => $this->getNumbers()->get('discount'),
            'newTotal' => $newTotal,
            'muchPrice' => $muchPrice
        ]);
    }

    /**
     * Send customer's info to Paytabs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate($this->rules());

        // Payment details
        $cartProducts = Cart::instance('default')->content();
        $productsPerTitle = implode(' || ', $cartProducts->pluck('name')->toArray());
        $productsPerQuantity = implode(' || ', $cartProducts->pluck('qty')->toArray());
        $unitPrice = implode(' || ', $cartProducts->pluck('price')->toArray());

        $subtotal = doubleval(Cart::subtotal(2, '.', ''));
        $tax = round((config('cart.tax') / 100) * $subtotal, 2);
        $totalPrice = $subtotal + $tax;
        $discount = session()->get('coupon')['discount'] ?? 0;

        // Checkout with Paytabs
        $pt = new Paytabs(config('services.paytabs.merchant_email'), config('services.paytabs.secret_key'));

        $result = $pt->create_pay_page($this->getPaymentInfo($request, $productsPerTitle, $productsPerQuantity, $unitPrice));

        if ($result->response_code != 4012) {
            return back()->withErrors('Something went wrong. Please try again!');
        }

        return redirect($result->payment_url);
    }

    /**
     * Verify that payment is successful
     *
     * @param \Illuminate\Http\Request $request
     */
    public function verify(Request $request)
    {
        $pt = new Paytabs(config('services.paytabs.merchant_email'), config('services.paytabs.secret_key'));

        $result = $pt->verify_payment($request->payment_reference);

        if ($result->response_code == 100) {
            Cart::instance('default')->destroy();
            session()->forget('coupon');

            return view('thankyou');
        } else {
            return redirect('checkout')->withErrors($result->result);
        }
    }

    protected function rules()
    {
        return [
            'email' => 'required|email',
            'phone_number' => 'required',
            'billing_address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required|numeric',
            'address_shipping' => 'required',
            'country_shipping' => 'required',
            'city_shipping' => 'required',
            'state_shipping' => 'required',
            'postal_code_shipping' => 'required|numeric',
            'cc_first_name' => 'required',
            'cc_last_name' => 'required',
            'cc_phone_number' => 'required',
        ];
    }

    protected function getPaymentInfo(Request $request, $productsPerTitle, $productsPerQuantity, $unitPrice)
    {
        return array(
            //Customer's Personal Information
            'cc_first_name' => $request->cc_first_name,
            'cc_last_name' => $request->cc_last_name,  
            'cc_phone_number' => $request->cc_phone_number,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            
            //Customer's Billing Address (All fields are mandatory)
            //When the country is selected as USA or CANADA, the state field should contain a String of 2 characters containing the ISO state code otherwise the payments may be rejected. 
            //For other countries, the state can be a string of up to 32 characters.
            'billing_address' => $request->billing_address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            
            //Customer's Shipping Address (All fields are mandatory)
            'address_shipping' => $request->address_shipping,
            'city_shipping' => $request->city_shipping,
            'state_shipping' => $request->state_shipping,
            'postal_code_shipping' => $request->postal_code_shipping,
            'country_shipping' => $request->country_shipping,
           
            //Product Information
            "products_per_title" => $productsPerTitle,   //Product title of the product. If multiple products then add “||” separator  ex: "Product1 || Product 2 || Product 4"
            'quantity' => $productsPerQuantity,                                    //Quantity of products. If multiple products then add “||” separator  ex: "1 || 1 || 1"
            'unit_price' => $unitPrice,                                  //Unit price of the product. If multiple products then add “||” separator.
            "other_charges" => $this->getNumbers()->get('tax'),                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.         
            'amount' => $this->getNumbers()->get('total'),                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
            'discount'=> $this->getNumbers()->get('discount'),                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
            'currency' => "USD",                                            //Currency of the amount stated. 3 character ISO currency code 
            
            //Invoice Information
            'title' => $request->cc_first_name . ' ' . $request->cc_last_name,               // Customer's Name on the invoice
            "msg_lang" => config('app.locale'),                 //Language of the PayPage to be created. Invalid or blank entries will default to English.(Englsh/Arabic)
            "reference_no" => "1231231",        //Invoice reference number in your system
           
            //Website Information
            "site_url" => config('services.paytabs.site_url'),      //The requesting website be exactly the same as the website/URL associated with your PayTabs Merchant Account
            'return_url' => route('checkout.verify'),
            "cms_with_version" => "API USING PHP",

            "paypage_info" => "1"
        );
    }

    protected function getNumbers()
    {
        $subtotal = doubleval(Cart::subtotal(2, '.', ''));
        $tax = round((config('cart.tax') / 100) * $subtotal, 2);
        $total = $subtotal + $tax;
        $discount = session()->get('coupon')['discount'] ?? 0;
        $newTotal = $total - $discount;

        return collect([
            "subtotal" => $subtotal,
            "tax" => $tax,
            "total" => $total,
            "discount" => $discount,
            "newTotal" => $newTotal
        ]);
    }
}
