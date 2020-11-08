<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\OrderProduct;
use App\Paytabs\Paytabs;
use App\Mail\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
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
        // Get cart from cookie to session
        $this->getFromCookieToSession('cart');

        // Delete "cart" cookie
        setcookie('cart', '', time()-60);

        // Delete "user_id" cookie
        setcookie('user_id', '', time()-3600);

        $total = getNumbers()->get('total');
        $cartIsEmpty = Cart::content()->isEmpty() ? 'Notice! Your cart is empty. Go to <a href="'.route('shop.index').'">shop</a> instead.' : null;
        $muchPrice = ($total > 5000 || $total < 0.27) && !$cartIsEmpty ? 'Notice! The total price of products should be between 0.27 and 5000.00 USD' : null;
        $productsAreNoLongerAvailable = $this->productsAreNoLongerAvailable();

        return view('checkout')->with([
            'subtotal' => getNumbers()->get('subtotal'),
            'tax' => getNumbers()->get('tax'),
            'newSubtotal' => getNumbers()->get('newSubtotal'),
            'discount' => getNumbers()->get('discount'),
            'discountType' => getNumbers()->get('discountType'),
            'discountPercent' => getNumbers()->get('discountPercent'),
            'total' => $total,
            'warnings' => [$muchPrice, $cartIsEmpty],
            'productsAreNoLongerAvailable' => $productsAreNoLongerAvailable
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
        // Check race condition when there are less items available to purchase
        if ($error = $this->productsAreNoLongerAvailable()) {
            return back();
        } 

        // Validation
        $request->validate($this->rules(), $this->messages());

        // Checkout with Paytabs
        $result = $this->createPayPageForPaytbas($request);

        // Add cart content to cookie so we can get it back to session if payment failed
        setcookie('cart', Cart::content()->toJson(), time()+600);

        // Add coupon to cookie
        if (session()->has('coupon')) {
            setcookie('coupon', json_encode(session()->get('coupon'), true), time()+600);
        }

        // If there is an error while creating the pay page
        if ($result->response_code != 4012) {
            // Insert into orders tables with error
            $this->addToOrdersTables($request, $result->result);

            return back()->withErrors('Something went wrong. Please try again!');
        }

        // Insert into orders tables without error
        $order = $this->addToOrdersTables($request);

        // Add wishlist to cookies becuase after the redirect, session is gonna be destroyed
        // We r gonna get it back to session in the "verify" method
        $wishlist = Cart::instance('wishlist');
        if ($wishlist->count()) {
            setcookie('wishlist', $wishlist->content()->toJson(), time()+600);
        }

        // Same as wishlist, we r gonna add the "user_id" to cookie, if not a guest
        // And use it later to authenticate them again
        if (auth()->user()) {
            setcookie('user_id', auth()->id(), time()+600);
        }

        // Add "latest_order_id" to cookie, so we can store errors to DB
        setcookie('latest_order_id', $order->id, time()+600);

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

        // Get wishlist from cookie back to session
        $this->getFromCookieToSession('wishlist');

        // Delete "wishlist" cookie
        setcookie('wishlist', '', time()-3600);

        // Authenticate user if they were logged in
        if (! empty($id = $_COOKIE['user_id'] ?? null)) {
            \Auth::loginUsingId($id);
        }

        $order = Order::find($_COOKIE['latest_order_id']);

        // successful payment
        if ($result->response_code == 100) {
            $this->reduceProductsQuantity($order);

            Mail::send(new OrderPlaced($order));

            Cart::instance('default')->destroy();

            session()->forget('coupon');

            return redirect(route('thankyou'));
        }

        // Add error to the order in DB if payment failed
        $order->update([
            'error' => $result->result
        ]);

        setcookie('latest_order_id', '', time()-3600);

        // Get coupon from cookie to session
        if (! empty($coupon = $_COOKIE['coupon'] ?? null)) {
            session()->put('coupon', json_decode($coupon, true));
        }

        return redirect(route('checkout.index'))->withErrors($result->result);
    }

    protected function productsAreNoLongerAvailable()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);
            if ($product->quantity < $item->qty) {
                return 'Sorry! One of the items in your cart is no longer available.';
            }
        }

        return false;
    }

    protected function getFromCookieToSession($index)
    {
        if (! empty($items = $_COOKIE[$index] ?? null)) {
            foreach (json_decode($items) as $itemId => $item) {
                Cart::instance($index == 'wishlist' ? 'wishlist' : 'default')
                    ->add($item->id, $item->name, 1, $item->price)
                    ->associate('App\Product');
            }
        }
    }

    protected function reduceProductsQuantity(Order $order)
    {
        foreach ($order->products as $product) {
            $product->update([
                'quantity' => $product->quantity - $product->pivot->quantity
            ]);
        }
    }

    protected function rules()
    {
        $emailRule = auth()->user() ? 'required|email' : 'required|email|unique:users';
        return [
            'email' => $emailRule,
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

    protected function messages()
    {
        return [
            'email.unique' => 'You already have an account with this email address. Please <a href="'.route('login').'">login</a> to continue.'
        ];
    }

    protected function createPayPageForPaytbas(Request $request)
    {
        $pt = new Paytabs(config('services.paytabs.merchant_email'), config('services.paytabs.secret_key'));

        return $pt->create_pay_page($this->getPaymentInfo($request));
    }

    protected function getPaymentInfo(Request $request)
    {
        // Payment details
        $cartProducts = Cart::instance('default')->content();
        $productsPerTitle = implode(' || ', $cartProducts->pluck('name')->toArray());
        $productsPerQuantity = implode(' || ', $cartProducts->pluck('qty')->toArray());
        $unitPrice = implode(' || ', $cartProducts->pluck('price')->toArray());

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
            "other_charges" => getNumbers()->get('tax'),                                     //Additional charges. e.g.: shipping charges, taxes, VAT, etc.         
            'amount' => getNumbers()->get('total'),                                          //Amount of the products and other charges, it should be equal to: amount = (sum of all products’ (unit_price * quantity)) + other_charges
            'discount'=> getNumbers()->get('discount'),                                                //Discount of the transaction. The Total amount of the invoice will be= amount - discount
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

    protected function addToOrdersTables(Request $request, $error = null)
    {
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->id() : null,
            'billing_email' => $request->email,
            'billing_phone' => $request->phone_number,
            'billing_address' => $request->billing_address,
            'billing_country' => $request->country,
            'billing_city' => $request->city,
            'billing_state' => $request->state,
            'billing_postal_code' => $request->postal_code,
            'shipping_address' => $request->address_shipping,
            'shipping_country' => $request->country_shipping,
            'shipping_city' => $request->city_shipping,
            'shipping_state' => $request->state_shipping,
            'shipping_postal_code' => $request->postal_code_shipping,
            'cc_first_name' => $request->cc_first_name,
            'cc_last_name' => $request->cc_last_name,  
            'cc_phone' => $request->cc_phone_number,
            'subtotal' => getNumbers()->get('subtotal'),
            'tax' => getNumbers()->get('tax'),
            'discount' => getNumbers()->get('discount'),
            'discount_code' => getNumbers()->get('discountCode'),
            'total' => getNumbers()->get('total'),
            'payment_gateway' => 'paytabs',
            'error' => $error
        ]);

        session()->put('latest_order_id', $order->id);

        foreach (Cart::content() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->model->id,
                'quantity' => $item->qty
            ]);
        }

        return $order;
    }
}
