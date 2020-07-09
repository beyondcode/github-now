<?php

use App\Http\Controllers\SpotifyController;
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

Route::get('/', 'PreviewController@index');

Route::get('/spotify/authorize', [SpotifyController::class, 'authorizeApplication'])->name('spotify.authorize');
Route::get('/spotify/callback', [SpotifyController::class, 'storeTokens'])->name('spotify.callback');
