<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use App\Services\CartService;
use App\Services\ItemsService;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view)
        {
			$agent = new Agent();	
			$view->with('isMobile', $agent->isMobile() );		
			$view->with('guestEnabled',  config('uni.guest_login') );
			
			$files = array();
			$dir 	= $_SERVER['DOCUMENT_ROOT'].'/ad-files/';
			foreach (glob($dir."/*.*") as $file) {
				$ext	= pathinfo(basename($file), PATHINFO_EXTENSION);
				$fn		= pathinfo(basename($file), PATHINFO_FILENAME);
				$files[]= [basename($dir).'/'.basename($file), $fn, $ext];
			};		
			$view->with('adFiles',  $files );
			
//			$view->with('htmlFiles', Cache::remember('htmlFiles', 0, function () {
//				$files = array();
//				$dir 	= $_SERVER['DOCUMENT_ROOT'].'/html/';
//				foreach (glob($dir."/*.html") as $file) {
//					$files[] = [basename($dir).'/'.basename($file), basename($file, '.html')];
//				};		
//                return $files;
//            }));
			
            $view->with('itemsInCart', CartService::getNumberOfItemsInCart());
            $view->with('sumOfCart', CartService::getSumOfItemsInCart());
            $view->with('searchProductGroups', Cache::remember('searchProductGroups', 10, function () {
                return ItemsService::getProductCategories('NULL');
            }));
            $view->with('productTree', Cache::remember('productTree', 10, function () {
                return ItemsService::getProductTree();
            }));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
