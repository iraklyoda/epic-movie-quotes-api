<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
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
		$token = auth()->attempt($request->only($login_type, 'password'));

		if (!$token)
		{
			return response()->json(['error' => 'User Does not exist!'], 404);
		}
		return $this->respondWithToken($token);
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
}
