<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::get('customer/{id}', 'CustomerController@show');
    Route::post('customer', 'CustomerController@add');
    Route::delete('customer/{id}', 'CustomerController@delete');
    Route::post('customer/search', 'CustomerController@search');
    Route::put('customer/{id}', 'CustomerController@update');
});

Route::post('v1/register', 'RegisterController@register');
Route::post('v1/login', 'LoginController@login');
