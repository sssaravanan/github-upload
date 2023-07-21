<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['products'] = Product::get();
        return view('home', $data);
    }

    public function buy(Request $request, $id) {
        $data['product'] = Product::findOrFail($id);
        return view('buy', $data);
    }

    public function payment(Request $request) {
        $user           = auth()->user();
        $paymentMethod  = $request->input('payment_method');
        $product        = Product::find($request->input('product_id'));

        try {
            $customerAddress = [
                'line1'         => $request->input('address'),
                'city'          => $request->input('city'),
                'state'         => $request->input('state'),
                'postal_code'   => $request->input('postal_code'),
                'country'       => $request->input('country'),
            ];

            // $customer = $user->createOrGetStripeCustomer();
            // $user->createOrGetStripeCustomer();
            $customer = $user->updateStripeCustomer([
                'name'      => $request->input('card_holder_name'),
                'phone'     => $request->input('phone_number'),
                'address'   => $customerAddress,
            ]);

            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->charge($product->price * 100, $paymentMethod, [
                'currency'      => env('CURRENCY'),
                'off_session'   => true,
                'confirm'       => true,
                'customer'      => $customer->id,
                'description'   => 'Amount paid for - '.$product->name,
            ]);

            // $user->charge($product->price * 100, $paymentMethod, ['off_session' => true]);
        } catch (\Exception $exception) {
            return back()->with('danger', $exception->getMessage());
        }

        return redirect()->route('home')->with('success', 'Payment completed. Product purchased successfully!!');
    }
}
