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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('verified')->name('home');

Route::prefix('card')->middleware(['auth'])->group(function (){  
    Route::get('/{set}', 'CardsController@listCardsBy');
    Route::get('/', 'CardsController@listCards')->name('cards');  
    Route::post('/save', 'DecksController@saveDecks');
}); 

Route::prefix('deck')->middleware(['auth'])->group(function (){
    Route::get('/', 'DecksController@myDecks')->name('decks');
    Route::get('/{deck_id}/cards', 'DecksController@specificDeck');
});

Route::prefix('import')->middleware(['auth', 'is_admin'])->group(function (){
    Route::get('/', 'ImportController@getImport')->name('import');
    Route::post('/', 'ImportController@saveImport')->name('import_post');
    Route::get('/importing/{id}', 'ImportController@importing')->name('importing');
    Route::post('/process', 'ImportController@process')->name('process');
});

Route::get('/contact', 'ContactController@showContact')->name('contact.show');
Route::post('/contact', 'ContactController@sendEmail')->name('contact.send');

Route::get('/admin', 'AdminController@admin')
    ->middleware('is_admin')
    ->name('admin');
