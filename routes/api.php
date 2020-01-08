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

Route::prefix('auth')->group(function () {
  Route::post('register', 'ProfileController@register');
  Route::post('login', 'ProfileController@login');
  Route::get('refresh', 'ProfileController@refresh');

  Route::group(['middleware' => 'auth:api'], function(){
      Route::get('user', 'ProfileController@user');
      Route::post('logout', 'ProfileController@logout');
  });
});