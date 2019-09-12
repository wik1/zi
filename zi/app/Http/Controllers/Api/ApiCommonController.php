<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\ItemsService;
use App\Http\Controllers\Controller;

class ApiCommonController extends Controller
{
    public function menu()
    {
        $array = array(
            (object)array(
                'name' => __('messages.menu.promocje'),
                'url' => '/discounts'
            ),
            (object)array(
                'name' => 'Wyprzedaż',
                'url' => '/sale'
            ),
            (object)array(
                'name' => 'Produkty',
                'url' => '/products'
            ),
            (object)array(
                'name' => 'Koszyk',
                'url' => '/cart'
            ),
            (object)array(
                'name' => 'Zamówienia',
                'url' => '/orders'
            )
        );
        echo json_encode($array);
    }
}

