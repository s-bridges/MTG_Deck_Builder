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

Route::get('/404', function () {
    return view('errors.404');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('verified')->name('home');

Route::prefix('card')->middleware(['guest'])->group(function (){  
    Route::get('/{set}', 'CardsController@listCardsBy');
    Route::get('/', 'CardsController@listCards')->name('cards');
}); 

Route::post('/card/save', 'DecksController@saveDecks')->middleware(['auth']);

Route::prefix('deck')->middleware(['guest'])->group(function (){
    Route::get('/', 'DecksController@myDecks')->name('decks');
    Route::get('/{deck_id}/cards', 'DecksController@specificDeck');
    Route::get('/dotw', 'DecksController@deckOfTheWeek');
    Route::put('/edit', 'DecksController@editDeck')->middleware(['auth']);
    Route::post('/{deck_id}/delete', 'DecksController@deleteDeck')->middleware(['auth']); //delete route to be built
});


Route::prefix('import')->middleware(['auth', 'is_admin'])->group(function (){
    Route::get('/', 'ImportController@getImport')->name('import');
    Route::post('/', 'ImportController@saveImport')->name('import_post');
    Route::get('/importing/{id}', 'ImportController@importing')->name('importing');
    Route::post('/process', 'ImportController@process')->name('process');
});

Route::get('/contact', 'ContactController@showContact')->name('contact.show');
Route::post('/contact', 'ContactController@sendEmail')->name('contact.send');

Route::prefix('admin')->middleware('is_admin')->group(function (){
    Route::get('/', 'AdminController@admin')->name('admin');
    Route::patch('/save/update-dotw/', 'AdminController@deckOfTheWeekSave')->name('update-dotw');
    Route::get('/import-cards', 'AdminController@importCards');
    Route::get('/api/connect', 'AdminController@connect');
});

Route::prefix('user')->middleware(['auth'])->group(function() {
    Route::get('/', 'UserController@profile')->name('profile');
});

Route::get('/health', 'HealthController@health');
