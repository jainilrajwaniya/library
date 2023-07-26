<?php

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

//LOGGED IN ROUTES
$router->group(['middleware' => 'auth:sanctum'], function ($router) {
    $router->post('books', 'BooksController@save');
    $router->put('books/{id}', 'BooksController@edit');
    $router->get('books', 'BooksController@get');
    $router->delete('books/{id}', 'BooksController@delete');

    $router->post('checkouts', 'CheckoutController@save');
    $router->put('checkouts/{id}', 'CheckoutController@returnBook');
});

//NON-LOGGED IN ROUTES
Route::post('signin', 'AuthController@signin');
Route::post('signup', 'AuthController@signup');
Route::get('loginerror', 'AuthController@loginError');
