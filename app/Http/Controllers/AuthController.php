<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function register(StoreUserRequest $request): JsonResponse
	{
		User::create([
			'username' => $request->username,
			'email'    => $request->email,
			'password' => $request->password,
		]);
		$token = auth()->attempt($request->validated());
		if (!$token)
		{
			return response()->json(['error' => 'Not registered!'], 404);
		}
		return $this->respondWithToken($token);
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

//    public function user(): JsonResponse
//    {
//        return response()->json(auth()->user(), 200);
//    }
//
	public function logout(): JsonResponse
	{
		auth()->logout();
		return response()->json(['message' => 'Successfully logged out']);
	}
//
//    public function refresh(): JsonResponse
//    {
//        return $this->respondWithToken(auth()->refresh());
//    }

	private function respondWithToken(string $token): JsonResponse
	{
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}
}
