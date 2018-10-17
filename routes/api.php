<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/decks', 'DeckController@store');
Route::get('/decks/{deck}', 'DeckController@show');
Route::put('/decks/{deck}', 'DeckController@update');
Route::delete('/decks/{deck}', 'DeckController@destroy');