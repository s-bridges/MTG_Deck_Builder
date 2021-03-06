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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'HomeController@welcome')->name('welcome');

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/404', function () {
    return view('errors.404');
});

Auth::routes(['verify' => true]);

Route::prefix('card')->group(function (){
    Route::get('/list-set/{set}', 'CardsController@listCardsBy'); 
    Route::get('/{id}', 'CardsController@cardLanding')->name('card-landing'); 
    Route::get('/', 'CardsController@listCards')->name('cards');
}); 


Route::post('/card/save', 'DecksController@saveDecks')->middleware(['auth']);

Route::prefix('deck')->group(function (){
    Route::get('/', 'DecksController@myDecks')->name('decks');
    Route::get('/{deck_id}/cards', 'DecksController@specificDeck');
    Route::get('/dotw', 'DecksController@deckOfTheWeek');
    // Route::put('/edit', 'DecksController@editDeck')->middleware(['auth']);
    Route::put('/edit', 'DecksController@editDeckSave')->middleware(['auth']);

    Route::delete('/{deck_id}/delete', 'DecksController@deleteDeck')->middleware(['auth']);
    Route::get('/all', 'DecksController@listAllDecks')->name('alldecks'); 
});

Route::prefix('blog')->group(function (){
    Route::get('/all', 'BlogController@allBlogs');
    Route::get('/{slug}', 'BlogController@showBlog')->name('blogPost'); //<--comments are displayed here
    Route::post('/comment/save', 'BlogController@saveComment')->name('saveComment');
    Route::delete('/comment/{id}/delete', 'BlogController@deleteComment')->name('deleteComment');
    Route::put('/comment/{id}/like', 'BlogController@likeComment')->name('likeComment');
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
    Route::patch('/update/user_type', 'AdminController@changeUserType')->name('changeUserType');
    Route::get('/import-cards', 'AdminController@importCards');
    Route::get('/api/connect', 'AdminController@connect');
    //Route::get('/blog', 'AdminController@createBlogPost')->name('blog');
    Route::get('/blog/{slug}', 'AdminController@editBlogPost')->name('blog');
    Route::post('/blog/save', 'AdminController@saveBlogPost')->name('saveBlogPost');
    Route::post('/blog/update', 'AdminController@updateBlogPost')->name('updateBlogPost');
    // Route::delete('/{slug}/delete', 'BlogController@deletePost')->name('post_delete'); */
    // route for updating card power levels
    Route::get('/cards/power-levels', 'AdminController@powerLevels')->name('powerLevels');
    Route::post('/cards/power-levels/update', 'AdminController@syncPowerLevels')->name('syncPowerLevels');
});

Route::prefix('user')->group(function() {
    Route::get('/', 'UserController@profile')->name('profile');
});

Route::get('/health', 'HealthController@health');

Route::prefix('editor')->middleware(['auth', 'is_editor'])->group(function () {
    Route::get('/', 'EditorController@editor')->name('editor');
    Route::get('/blog', 'EditorController@createBlogPost')->name('blog');
    Route::get('/blog/{slug}', 'EditorController@editBlogPost')->name('blog');
    Route::post('/blog/save', 'EditorController@saveBlogPost')->name('saveBlogPost');
    Route::post('/blog/update', 'EditorController@updateBlogPost')->name('updateBlogPost');
    Route::delete('/{slug}/delete', 'EditorController@deletePost')->name('post_delete');
});

Route::prefix('catalog')->middleware(['auth'])->group(function () {
    Route::get('/', 'CatalogController@viewCatalog')->name('catalog');
    //Route::get('/cards', 'CatalogController@listCards')->name('catalog');
    Route::post('/save', 'CatalogController@saveCatalog');
});