<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome');

Route::get('contact', function () {
    return view('pages.contact');
});

Auth::routes();

/* login */
Route::get('/user/login', function()	{ return view('auth.login', ['silentAuth' => false, 'stdAuth' => false]); } );
Route::post('/user/login', 'Auth\CustomLoginController@userUpdate');
Route::get('/user/login/guest', 'Auth\CustomLoginController@guestLogin');
Route::get('/user/register', function()		{ return view('auth.register', ['showErorrs' => false]); } );
Route::get('/user/reregister', function()	{ return view('auth.register', ['showErorrs' => true]); } );
Route::post('/user/register', 'Auth\CustomLoginController@userAdd');
Route::get('/user/m/choice', function()	{ return view('auth.mChoice'); } );


Route::get('/sale', 'ItemsController@sales');
Route::get('/discounts', 'ItemsController@discounts');
Route::get('/products', 'ItemsController@productsHome');
Route::get('/cart', 'CartController@home');
Route::post('/cart/update', 'CartController@update');
Route::get('/cart/summary', 'CartController@summary');
Route::post('/cart/submit', 'CartController@submit');
Route::post('/cart/summary/update', 'CartController@summaryUpdate');
Route::get('/cart/removeall', 'CartController@removeAll');
Route::get('/products/{productCategoryId}', 'ItemsController@productCategory');

Route::get('/item/{id}', 'ItemsController@item');

Route::get('/account', 'AccountController@home');
Route::get('/account/finances', 'AccountController@finances');
Route::get('/account/orders', 'AccountController@orders');
Route::get('/account/orders/{id}', 'AccountController@order');
Route::get('/account/invoices', 'AccountController@invoices');
Route::get('/account/invoices/{id}', 'AccountController@invoice');

Route::get('/devtools/tableviewer', 'DevToolsController@tableViewer');

Route::get('/search', 'ItemsController@search');
Route::get('/m/search', 'ItemsController@mSearchV');


Route::get('grid/{id}',function($id){
   return view('pages.grid');
});


