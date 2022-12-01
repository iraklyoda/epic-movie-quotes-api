<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\StorePasswordResetRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
	public function resetRequest(StoreEmailRequest $request)
	{
		$validated = $request->validated();
		$status = Password::sendResetLink(
			$request->only('email')
		);

		return $status === Password::RESET_LINK_SENT
			? response()->json(['status' => $status])
			: response()->json(['msg' => 'User not found'], 404);
	}

	public function resetPassword($token, StoreEmailRequest $request)
	{
		return redirect(env('VITE_APP_ROOT') . '?token=' . $token . '&email=' . $request->email);
	}

	public function updatePassword(StorePasswordResetRequest $request)
	{
		$attributes = [
			'email'                 => $request->email,
			'password'              => $request->password,
			'token'                 => $request->token,
		];

		$status = Password::reset(
			$attributes,
			function ($user, $password) {
				$user->forceFill([
					'password' => $password,
				])->setRememberToken(Str::random(60));
				$user->save();
			}
		);
		return $status === Password::PASSWORD_RESET
			? response()->json(['status' => $status])
			: response()->json(['msg' => 'Not Updated'], 404);
	}
}
