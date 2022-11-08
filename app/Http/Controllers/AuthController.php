<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $remember = $request->remember_token;
        /** @var \App\Models\User $user */
        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->password,
        ]);
        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
