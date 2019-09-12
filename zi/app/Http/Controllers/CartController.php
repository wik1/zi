<?php
/**
 * Created by PhpStorm.
 * User: M&P
 * Date: 22.04.2017
 * Time: 19:25
 */

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\ItemsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function home()
    {
        return view('cart', [
            'cart' => CartService::getCartWithItemsInfo(),
            'nettoSumOfCart' => CartService::getNettoSumOfItemsInCart(),
            'paymentTypes' => CartService::getPaymentTypes(Auth::user()->ktrh),
            'transportTypes' => CartService::getTransportTypes(Auth::user()->ktrh),
			'ktrhItems' => ItemsService::getKtrh(Auth::user()->ktrh),
			'useOdbiorca'=> config('uni.use_odbiorca')
        ]);
    }

    public function removeAll()
    {
        CartService::removeAll();
        return redirect('/discounts');
    }

    public function update(Request $request) {
        CartService::updateCartWithRequest($request->all(), $request->session(), Auth::user()->ktrh);
        return redirect('/cart');
    }

    public function summary(Request $request)
    {
        return view('cartSummary', [
            'cartSummary' => CartService::getCartSummary($request->session(), Auth::user()->ktrh),
            'paymentTypes' => CartService::getPaymentTypes(Auth::user()->ktrh),
            'transportTypes' => CartService::getTransportTypes(Auth::user()->ktrh)
        ]);
    }

    public function summaryUpdate(Request $request)
    {
        CartService::updateCartSummaryWithRequest($request->all(), $request->session(), Auth::user()->ktrh);
        return redirect('/cart/summary');
    }

    public function submit(Request $request)
    {
        $orderNumber = CartService::submitCart($request->session(), Auth::user()->ktrh);
		
        if ($orderNumber>0) {
            return redirect("/account/orders/{$orderNumber}");
        } else {
            return redirect('/cart');
        };
    }
}