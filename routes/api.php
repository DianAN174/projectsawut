<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
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

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // ...
    // public routes
    Route::post('/login', 'App\Http\Controllers\Auth\ApiAuthController@login')->name('login.api');
    Route::post('/register','App\Http\Controllers\Auth\ApiAuthController@register')->name('register.api');
    // our routes to be protected will go in here
    Route::post('/logout', 'App\Http\Controllers\Auth\ApiAuthController@logout')->name('logout.api');
    Route::middleware('auth:api')->group(function () {
        Route::get('/articles', 'App\Http\Controllers\ArticleController@index')->middleware('api.admin')->name('articles');
        Route::post('/articles', 'App\Http\Controllers\ArticleController@store')->middleware('api.admin')->name('articles');
        Route::get('/articles/{id}', 'App\Http\Controllers\ArticleController@show')->middleware('api.admin')->name('articles');
        Route::get('/articles/{id}/edit', 'App\Http\Controllers\ArticleController@edit')->middleware('api.admin')->name('articles');
        Route::put('/articles/{id}', 'App\Http\Controllers\ArticleController@update')->middleware('api.admin')->name('articles');
        Route::delete('/articles/{id}', 'App\Http\Controllers\ArticleController@delete')->middleware('api.admin')->name('articles');
        });
});