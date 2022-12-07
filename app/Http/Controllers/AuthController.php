<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUserUpdateRequest;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function register(StoreUserRequest $request)
	{
		$default_profile_picture = '/storage/images/profile/darth_vader_default_profile.png';
		$user = User::create([
			'username'        => $request->username,
			'email'           => $request->email,
			'password'        => $request->password,
			'profile_picture' => $default_profile_picture,
		])->sendEmailVerificationNotification();
	}

	public function login(StoreLoginRequest $request)
	{
		$login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)
			? 'email'
			: 'username';
		$request->merge([
			$login_type => $request->input('username'),
		]);
		$authenticated = auth()->attempt($request->only($login_type, 'password'));
		$user = auth()->user();
		if (!$authenticated)
		{
			$email = Email::where('email', '=', $request->username)->first();
			if ($email)
			{
				if ($email->is_email_verified === 1)
				{
					$email = Email::where('email', '=', $request->username)->first()->user->email;
					$authenticated = auth()->attempt([
						'email'    => $email,
						'password' => $request->password,
					]);
					$user = auth()->user();
				}
				else
				{
					return response()->json(['error' => 'Email is not verified'], 404);
				}
			}
		}
		if (!$authenticated)
		{
			return response()->json(['error' => 'Wrong email or password!'], 404);
		}

		if (!$user->hasVerifiedEmail())
		{
			return response()->json(['error' => 'Email is not verified'], 404);
		}

		$payload = [
			'exp' => Carbon::now()->addDays($request->remember_me ? 2 : 1)->timestamp,
			'uid' => auth()->user()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
		$cookie = cookie('access_token', $jwt, 100, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
		return response()->json('success', 200)->withCookie($cookie);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
		return response()->json('success', 200)->withCookie($cookie);
	}

	private function respondWithToken(string $token): JsonResponse
	{
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}

	public function updateUser(StoreUserUpdateRequest $request)
	{
		$user = JwtUser();
		if ($request->username)
		{
			$user->username = $request->username;
		}
		if ($request->new_password)
		{
			$user->password = $request->new_password;
		}
		if ($request->file('profile_picture'))
		{
			$file_name = time() . '_' . request()->file('profile_picture')->getClientOriginalName();
			$file_path = request()->file('profile_picture')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
			$user->profile_picture = '/storage/' . $file_path;
		}
		if ($user->save())
		{
			return response()->json('works', 200);
		}
		else
		{
			return response()->json(['error' => 'problem updating user'], 404);
		}
	}

	public function me(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				'user'    => jwtUser()->load(['emails' => function ($query) {
					$query->where('primary', '=', 0);
				}]),
			],
			200
		);
	}
}
