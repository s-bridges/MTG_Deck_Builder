<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('deck')->middleware(['auth'])->group(function (){
    Route::get('/', 'DecksController@index');
    Route::get('/deck/{id}', 'DecksController@show');
    Route::post('deck/add', 'DecksController@create');
    Route::put('{id}/edit-deck', 'DecksController@edit');
    Route::delete('{id}/delete-deck', 'DecksController@delete');
});

Route::resource('card', 'CardsController');
