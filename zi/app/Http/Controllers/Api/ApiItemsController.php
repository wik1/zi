<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\ItemsService;
use App\Http\Controllers\Controller;
use  Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class ApiItemsController extends Controller
{
    public function sales(Request $request)
    {
        $skip = $request->input('skip');
        $take = $request->input('take');
        return ItemsService::getItemsFromGroup('GRP88', $skip, $take, Auth::user()->ktrh, Auth::user()->is_netto);
    }

    public function itemPicture($id) {
        $picture = ItemsService::getPicture($id);
        header("Content-type: image/jpeg");
        echo $picture;
    }

    public function productCategories($productCategoryId) {
        return ItemsService::getProductCategories($productCategoryId);
    }

    public function items(Request $request) {
        $skip = $request->input('skip');
        $take = $request->input('take');
        $group = $request->input('group');
        return ItemsService::getItemsFromGroup($group, $skip, $take, Auth::user()->ktrh, Auth::user()->is_netto);
    }
}

