<?php
/**
 * Created by PhpStorm.
 * User: M&P
 * Date: 24.04.2017
 * Time: 21:31
 */

namespace App\Http\Controllers;

use View;
use App\Services\CartService;

class BaseViewController extends Controller
{
    public function __construct() {
        //View::share ( 'itemsInCart', CartService::getNumberOfItemsInCart() );
        $this->middleware('auth');
    }
}