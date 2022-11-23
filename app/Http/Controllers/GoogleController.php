<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function loginWithGoogle()
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function callbackFromGoogle()
	{
		try
		{
			$google_user = Socialite::driver('google')->stateless()->user();

			// Check Users Email If Already There
			$user = User::where('google_id', $google_user->getEmail())->first();
			if (!$user)
			{
				$new_user = User::updateOrCreate([
					'google_id' => $google_user->getId(),
				], [
					'username'     => $google_user->getName(),
					'email'        => $google_user->getEmail(),
				]);
				$payload = [
					'exp' => Carbon::now()->addDays(2)->timestamp,
					'uid' => User::where('username', '=', $google_user->getName())->first()->id,
				];
				$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
				$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return redirect(env('VITE_APP_ROOT'))->withCookie($cookie);
			}
			else
			{
				$payload = [
					'exp' => Carbon::now()->addDays(2)->timestamp,
					'uid' => User::where('username', '=', $google_user->getName())->first()->id,
				];
				$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
				$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return redirect(env('VITE_APP_ROOT'))->withCookie($cookie);
			}
		}
		catch (\Throwable $th)
		{
			dd('Something went wrong! ' . $th->getMessage());
		}
	}
}
