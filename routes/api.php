<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\NotificationController;
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
	Route::post('/profile/update-user', 'updateUser')->name('auth.update_user');
	Route::get('/me', 'me')->middleware('jwt.auth')->name('me');
	Route::get('/logout', 'logout')->middleware('jwt.auth')->name('auth.logout');
});

Route::group(['controller' => EmailsController::class], function () {
	Route::post('/profile/create-email', 'create')->name('email.create');
	Route::get('/profile/email/verify/{id}', 'verify')->name('email.verification');
	Route::post('/profile/email/make-primary/{email:id}', 'makePrimary')->name('email.make_primary');
	Route::post('/profile/email/delete/{email:id}', 'destroy')->middleware('jwt.auth')->name('email.delete');
});

Route::group(['controller' => GoogleController::class], function () {
	Route::get('google/login', 'loginWithGoogle')->name('google.login');
	Route::get('google/callback', 'callbackFromGoogle')->name('google.callback');
});

Route::group(['controller' => VerificationController::class], function () {
	Route::get('/email/verify/{id}', 'verify')->name('verification.verify'); // Make sure to keep this as your route name
});

Route::group(['controller' => ResetPasswordController::class], function () {
	Route::post('forgot-password', 'resetRequest')->name('password.email');
	Route::get('/reset-password/{token}', 'resetPassword')->name('password.reset');
	Route::post('/reset-password', 'updatePassword')->name('password.update');
});

Route::group(['controller' => MoviesController::class], function () {
	Route::post('/movies/create', 'create')->name('movie.create');
	Route::get('/movies/read', 'read')->middleware('jwt.auth')->name('movies.read');
	Route::post('/movies/search', 'search')->name('movies.search');
	Route::get('/movies/movie/{movie:id}', 'show')->name('movie.show');
	Route::post('/movies/movie/{movie:id}', 'update')->name('movie.update');
	Route::post('/movies/movie/delete/{movie:id}', 'destroy')->name('movie.delete');
});

Route::group(['controller' => QuotesController::class], function () {
	Route::post('/quotes/create', 'create')->name('quotes.create');
	Route::get('/quotes/read', 'read')->middleware('jwt.auth')->name('quotes.read');
	Route::post('/quotes/get', 'readNumber')->name('quotes.get');
	Route::post('/quotes/search', 'search')->name('quotes.search');
	Route::get('/quotes/quote/{quote:id}', 'show')->middleware('jwt.auth')->name('quote.show');
	Route::post('/quotes/quote/{quote:id}', 'update')->name('quote.update');
	Route::post('/quotes/quote/delete/{quote:id}', 'destroy')->name('quote.delete');
});

Route::group(['controller' => QuoteCommentsController::class], function () {
	Route::post('/quotes/{quote:id}/comments', 'create')->name('quote_comment.create');
});

Route::group(['controller' => NotificationController::class], function () {
	Route::get('/notifications', 'read')->middleware('jwt.auth')->name('notifications.read');
	Route::post('/notifications/mark-read', 'markRead')->middleware('jwt.auth')->name('notifications.mark_read');
});

Route::group(['controller' => LikesController::class], function () {
	Route::post('/quote/{quote:id}/like', 'create')->name('like.create');
});
