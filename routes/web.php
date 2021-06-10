<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
Route::get('/wakaf/carbon', 'App\Http\Controllers\Wakaf\Tahun@Coba');

Route::resource('/role', 'App\Http\Controllers\RoleController')->except([
    'create', 'show', 'edit', 'update'
]);
Route::resource('/users', 'App\Http\Controllers\UserController')->except([
    'show'
]);
Route::get('/users/roles/{id}', 'App\Http\Controllers\UserController@roles')->name('users.roles');
Route::put('/users/roles/{id}', 'App\Http\Controllers\UserController@setRole')->name('users.set_role');
Route::post('/users/permission', 'App\Http\Controllers\UserController@addPermission')->name('users.add_permission');
Route::get('/users/role-permission', 'App\Http\Controllers\UserController@rolePermission')->name('users.roles_permission');
Route::put('/users/permission/{role}', 'App\Http\Controllers\UserController@setRolePermission')->name('users.setRolePermission');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/chart-js', 'App\Http\Controllers\ChartJSController@index')->name('index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
