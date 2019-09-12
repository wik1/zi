<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('/sessionput/{id}/{value}', 'Api\ApiUniToolsController@sessionPut');
Route::get('/sessionget/{id}', 'Api\ApiUniToolsController@sessionGet');
Route::get('/sessionpull/{id}', 'Api\ApiUniToolsController@sessionPull');

Route::get('/sales', 'Api\ApiItemsController@sales');
Route::put('/cart/addproduct', 'Api\ApiCartController@addProductToCart');
Route::put('/cart/removeproduct', 'Api\ApiCartController@removeProductFromCart');
Route::put('/cart/submit', 'Api\ApiCartController@submit');
Route::get('/sales/items/{id}/picture', 'Api\ApiItemsController@itemPicture');

Route::get('/product/categories/{productCategoryId}', 'Api\ApiItemsController@productCategories');
Route::get('/items', 'Api\ApiItemsController@items');

Route::get('/common/menu', 'Api\ApiCommonController@menu');

Route::get('/devtools/tableviewer/{tableName}', 'DevToolsController@tableData');
