<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function register(StoreUserRequest $request)
	{
		$user = User::create([
			'username' => $request->username,
			'email'    => $request->email,
			'password' => $request->password,
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

		if (!$authenticated)
		{
			return response()->json(['error' => 'Wrong email or password!'], 404);
		}

		$payload = [
			'exp' => Carbon::now()->addDays(2)->timestamp,
			'uid' => User::where('username', '=', $request->username)->first()->id,
		];
		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
		return response()->json('success', 200)->withCookie($cookie);
	}

	public function logout(): JsonResponse
	{
		auth()->logout();
		return response()->json(['message' => 'Successfully logged out']);
	}

	private function respondWithToken(string $token): JsonResponse
	{
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}

	public function me(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				//				'user'    => jwtUser(),
			],
			200
		);
	}
}
