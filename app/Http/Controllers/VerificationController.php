<?php

namespace App\Http\Controllers;

use App\Models\email;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
	public function verify($user_id, Request $request)
	{
		if (!$request->hasValidSignature())
		{
			return response()->json(['msg' => 'Invalid/Expired url provided.'], 401);
		}
		$user = User::findOrFail($user_id);

		if (!$user->hasVerifiedEmail())
		{
			$user->markEmailAsVerified();
			Email::create([
				'email'             => $user->email,
				'user_id'           => $user->id,
				'primary'           => 1,
				'is_email_verified' => 1,
			]);
		}
		return redirect(env('VITE_APP_ROOT') . '?verified=yes');
	}

	public function resend()
	{
		if (auth()->user()->hasVerifiedEmail())
		{
			return response()->json(['msg' => 'Email already verified.'], 400);
		}

		auth()->user()->sendEmailVerificationNotification();

		return response()->json(['msg' => 'Email verification link sent on your email id']);
	}
}
