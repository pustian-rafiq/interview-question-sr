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
    return redirect()->to('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('product-variant', 'VariantController');
    // Route::resource('product', 'ProductController');
  
        //Product routes here
    Route::get("product/index",'ProductController@index')->name('product.index');
    Route::get("product/create",'ProductController@create')->name('product.create');
    Route::post("product/store",'ProductController@store');
    Route::get("product/edit/{id}",'ProductController@edit')->name('product.edit');
    Route::get("product/update/{id}",'ProductController@edit')->name('product.update');

    Route::get("product/search",'ProductController@searchProduct')->name('product.search');
    
    Route::resource('blog', 'BlogController');
    Route::resource('blog-category', 'BlogCategoryController');
});
