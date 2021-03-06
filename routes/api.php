<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::get('customers/search', 'CustomerController@getCustomer');
Route::resource('customers', 'CustomerController');
Route::get('suppliers/search', 'SupplierController@getSupplier');
Route::resource('suppliers', 'SupplierController');
Route::get('departments/search', 'DepartmentController@getDepartment');
Route::resource('departments', 'DepartmentController');

Route::get('products/search', 'ProductController@getProduct');
Route::resource('products', 'ProductController');
Route::resource('categories', 'CategoryController');

Route::get('prices/search', 'PriceController@getPrice');
Route::resource('prices', 'PriceController');
Route::resource('boms', 'BomController');
Route::get('contracts/getNewNumber/{customerId}', 'ContractController@getNewNumber');
Route::resource('contracts', 'ContractController');
Route::get('manufacturer-orders/search', 'ManufacturerOrderController@getManufacturerByStatus');
Route::resource('manufacturer-orders', 'ManufacturerOrderController');

Route::get('factories/search', 'FactoryController@getFactory');
Route::resource('factories', 'FactoryController');
Route::get('assignments/getNewNumber', 'AssignmentController@getNewNumber');
Route::resource('assignments', 'AssignmentController');
Route::resource('material-requisitions', 'MaterialRequisitionController');
Route::resource('good-deliveries', 'GoodDeliveryController');
Route::resource('good-receive', 'GoodReceiveController');

Route::get('stores/search', 'StoreController@getStore');
Route::resource('stores', 'StoreController');
