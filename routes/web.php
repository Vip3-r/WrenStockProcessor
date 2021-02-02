<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('landing');
});

Route::get('/products/create', 'App\Http\Controllers\ProductsController@create')->name('products.create');
Route::post('/products/store', 'App\Http\Controllers\ProductsController@store');

Route::get('/products/{id}', 'App\Http\Controllers\ProductsController@index')->name('products.show');
