<?php

namespace App\Http\Controllers;

class ItemsService
{
    public function getSales() {
        $sales[] = (object) array(
            'id' => 1,
            'name' => 'AQUAFORM wanna akryl.',
            'z' => true,
            'o' => false,
            'production_code' => 5903538238011,
            'net' => 219.00,
            'quantity' => 5
        );
        $sales[] = (object) array(
            'id' => 2,
            'name' => 'AQUAFORM wanna akryl. 2',
            'z' => true,
            'o' => false,
            'production_code' => 5903538238011,
            'net' => 219.00,
            'quantity' => 5
        );
        return $sales;
    }
}