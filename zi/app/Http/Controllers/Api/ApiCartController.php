<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiCartController extends Controller
{
    public function addProductToCart(Request $request)
    {
        return CartService::addProductToCart(
            $request->input('product_id'),
            $request->input('quantity'),
            $request->session(),
            Auth::user()->ktrh
        );
    }

    public function submit(Request $request)
    {
        return CartService::submitCart(
            $request->session(),
            Auth::user()->ktrh
        );
    }

}

