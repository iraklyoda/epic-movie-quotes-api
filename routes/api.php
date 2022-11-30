<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteCommentsController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

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

Route::group(['controller' => AuthController::class], function () {
	Route::post('/register', 'register')->name('auth.register');
	Route::post('/login', 'login')->name('auth.login');
	Route::get('/me', 'me')->middleware('jwt.auth')->name('me');
	Route::get('/logout', 'logout')->middleware('jwt.auth')->name('auth.logout');
});

Route::group(['controller' => GoogleController::class], function () {
	Route::get('google/login', 'loginWithGoogle')->name('google.login');
	Route::get('google/callback', 'callbackFromGoogle')->name('google.callback');
});

Route::group(['controller' => VerificationController::class, 'middleware' => 'jwt.auth'], function () {
	Route::get('/email/verify/{id}', 'verify')->name('verification.verify'); // Make sure to keep this as your route name
	Route::get('/email/resend', 'resend')->name('verification.resend');
});

Route::group(['controller' => ResetPasswordController::class, 'middleware' => 'jwt.auth'], function () {
	Route::post('forgot-password', 'resetRequest')->name('password.email');
	Route::get('/reset-password/{token}', 'resetPassword')->name('password.reset');
	Route::post('/reset-password', 'updatePassword')->name('password.update');
});

Route::group(['controller' => MoviesController::class], function () {
	Route::post('/movies/create', 'create')->name('movie.create');
	Route::get('/movies/read', 'read')->middleware('jwt.auth')->name('movies.read');
	Route::get('/movies/movie/{movie:id}', 'show')->middleware('jwt.auth')->name('movie.show');
	Route::post('/movies/movie/{movie:id}', 'update')->name('movie.update');
	Route::post('/movies/movie/delete/{movie:id}', 'destroy')->name('movie.delete');
});

Route::group(['controller' => QuotesController::class], function () {
	Route::post('/quotes/create', 'create')->name('quotes.create');
	Route::get('/quotes/read', 'read')->middleware('jwt.auth')->name('quotes.read');
	Route::get('/quotes/quote/{quote:id}', 'show')->middleware('jwt.auth')->name('quote.show');
	Route::post('/quotes/quote/{quote:id}', 'update')->name('quote.update');
	Route::get('/quotes/movie/{movie:id}', 'readMovieQuotes')->middleware('jwt.auth')->name('movie_quotes.read');
	Route::post('/quotes/quote/delete/{quote:id}', 'destroy')->name('quote.delete');
});

Route::group(['controller' => QuoteCommentsController::class], function () {
	Route::post('/quotes/{quote:id}/comments', 'create')->name('quote_comment.create');
});
