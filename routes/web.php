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

Route::prefix('card')->middleware(['auth'])->group(function (){
    Route::get('/', 'CardsController@listCards');
});

Route::prefix('deck')->middleware(['auth'])->group(function (){
    Route::get('/', 'DecksController@showDecks');
});

Route::prefix('import')->middleware(['auth'])->group(function (){
    Route::get('/', 'ImportController@getImport')->name('import');
    Route::post('import', 'ImportController@saveImport')->name('import_post');
    Route::get('/importing/{id}', 'ImportController@importing')->name('importing');
    Route::post('/process/', 'ImportController@process')->name('process');
});