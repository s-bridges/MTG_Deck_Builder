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

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/404', function () {
    return view('errors.404');
});

Auth::routes(['verify' => true]);

Route::prefix('card')->group(function (){  
    Route::get('/{set}', 'CardsController@listCardsBy');
    Route::get('/', 'CardsController@listCards')->name('cards');
}); 

Route::post('/card/save', 'DecksController@saveDecks')->middleware(['auth']);

Route::prefix('deck')->group(function (){
    Route::get('/', 'DecksController@myDecks')->name('decks');
    Route::get('/{deck_id}/cards', 'DecksController@specificDeck');
    Route::get('/dotw', 'DecksController@deckOfTheWeek');
    Route::put('/edit', 'DecksController@editDeck')->middleware(['auth']);
    Route::delete('/{deck_id}/delete', 'DecksController@deleteDeck')->middleware(['auth']);
    Route::get('/all', 'DecksController@listAllDecks')->name('alldecks'); 
});

Route::prefix('blog')->group(function (){
    Route::get('/all', 'BlogController@allBlogs');
    Route::get('/{slug}', 'BlogController@showBlog')->name('blogPost'); 
});


Route::prefix('import')->middleware(['auth', 'is_admin'])->group(function (){
    Route::get('/', 'ImportController@getImport')->name('import');
    Route::post('/', 'ImportController@saveImport')->name('import_post');
    Route::get('/importing/{id}', 'ImportController@importing')->name('importing');
    Route::post('/process', 'ImportController@process')->name('process');
});

Route::get('/contact', 'ContactController@showContact')->name('contact.show');
Route::post('/contact', 'ContactController@sendEmail')->name('contact.send');

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function (){
    Route::get('/', 'AdminController@admin')->name('admin');
    Route::patch('/save/update-dotw/', 'AdminController@deckOfTheWeekSave')->name('update-dotw');
    Route::get('/import-cards', 'AdminController@importCards');
    Route::get('/api/connect', 'AdminController@connect');
    Route::get('/blog', 'AdminController@createBlogPost')->name('blog');
    Route::get('/blog/{slug}', 'AdminController@editBlogPost')->name('blog');
    Route::post('/blog/save', 'AdminController@saveBlogPost')->name('saveBlogPost');
    Route::post('/blog/update', 'AdminController@updateBlogPost')->name('updateBlogPost');

});

Route::prefix('user')->group(function() {
    Route::get('/', 'UserController@profile')->name('profile');
});

Route::get('/health', 'HealthController@health');
