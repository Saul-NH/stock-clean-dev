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
    return view('welcome');
});

Route::post('/test','ProductoController@test')->name('test');

Auth::routes();

Route::resource('productos','ProductoController');
Route::resource('ventas', 'VentaController')->except([
     'edit', 'update', 'destroy', 'create'
]);

Route::get('/home', 'HomeController@index')->name('home');

