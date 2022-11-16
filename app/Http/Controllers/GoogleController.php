<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function loginWithGoogle()
	{
		return Socialite::driver('google')->redirect();
	}

	public function callbackFromGoogle()
	{
		try
		{
			$google_user = Socialite::driver('google')->user();

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
				$token = Auth::login($new_user);
				$expires_in = auth()->factory()->getTTL() * 60;
				return redirect(env('VITE_APP_ROOT') . '?token=' . $token . '&expires=' . $expires_in);
			}
			else
			{
				$token = Auth::login($user);
				$expires_in = auth()->factory()->getTTL() * 60;
				return redirect(env('VITE_APP_ROOT') . '?token=' . $token . '&expires=' . $expires_in);
			}
		}
		catch (\Throwable $th)
		{
			dd('Something went wrong! ' . $th->getMessage());
		}
	}
}
