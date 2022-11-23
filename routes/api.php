<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MoviesController;
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

Route::post('/movies/create', [MoviesController::class, 'create'])->name('posts.create');
Route::get('/movies/read', [MoviesController::class, 'read'])->middleware('jwt.auth')->name('posts.read');
