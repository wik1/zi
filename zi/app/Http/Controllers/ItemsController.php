<?php

namespace App\Http\Controllers;

require_once('Services/ItemsService.php');

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\ItemsService;
use App\Services\ConfigService;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;




class ItemsController extends BaseViewController
{
    public function sales(Request $request)
    {        
        $group = ConfigService::getPredefinedSalesGroup();
        return view('productList', [
            'results' => ItemsService::getItemsFromGroup($group->ID, Auth::user()->ktrh, Auth::user()->is_netto)
        ]);
    }	

    public function discounts()
    {	
        $group = ConfigService::getPredefinedDiscountsGroup();
        return view('productList', [
            'results' => ItemsService::getItemsFromGroup($group->ID, Auth::user()->ktrh, Auth::user()->is_netto),
            'userKind' => Auth::user()->kind()
        ]);
    }

    public function item($id)
    {
        $item = ItemsService::getItem( $id, Auth::user()->ktrh, Auth::user()->is_netto);
        return view('item', ['item' => $item]);
    }

    public function productsHome()
    {
        return view('productsHome', [
            'header' => NULL,
            'productTree' => ItemsService::getProductTree(),
            'productCategoryId' => 'NULL'
        ]);
    }

    public function productCategory($productCategoryId)
    {
		$productGroupDetails = ItemsService::getProductGroupDetails($productCategoryId);

		if ($productGroupDetails) {
			return view('productList', [
				'header' => $productGroupDetails->name,
				'breadcrumbs' => ItemsService::getBreadcrumbsForGroup($productGroupDetails),
				'results' => ItemsService::getItemsFromGroup($productCategoryId, Auth::user()->ktrh, Auth::user()->is_netto)
			]);
		} else 
			return redirect('/discounts');
    }

    public function search(Request $request)
    {	
        $query = $request->input('q');
        $category = $request->input('category');
        $results = ItemsService::getItemsByQuery($query, $category, Auth::user()->ktrh, Auth::user()->is_netto);
        return view('searchResultList', [
            'header' => 'Wyniki wyszukiwania',
            'results' => $results
        ]);
    }
	
	public function mSearchV(Request $request)
    {       
        return view('mSearchItems', [ 'header' => 'Wyniki wyszukiwania']);
    }

}
