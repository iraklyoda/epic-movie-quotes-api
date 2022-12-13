<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreatedEmailRequest;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailsController extends Controller
{
	public function create(StoreCreatedEmailRequest $request): JsonResponse
	{
		if (!jwtUser())
		{
			return response()->json(['message' => 'token not present'], 401);
		}
		$email = Email::create([
			'email'   => $request->email,
			'user_id' => jwtUser()->id,
		]);
		jwtUser()->sendEmailVerification($email->id, $email->email);
		return response()->json('Email created successfully!', 201);
	}

	public function verify($email_id, Request $request)
	{
		if (!$request->hasValidSignature())
		{
			return response()->json(['msg' => 'Invalid/Expired url provided.'], 410);
		}
		$email = Email::findOrFail($email_id);
		if (!$email)
		{
			return response()->json(['msg' => 'Email not found'], 404);
		}
		if (!$email->is_email_verified)
		{
			$email->is_email_verified = 1;
			$email->save();
		}
		return redirect(env('VITE_APP_ROOT') . 'profile/edit');
	}

	public function makePrimary(Email $email)
	{
		$user = $email->user()->first();
		$current_email = Email::where('email', '=', $user->email)->first();
		$current_email->primary = 0;
		$email->primary = 1;
		$user->email = $email->email;
		$user->save();
		$current_email->save();
		if ($current_email->save() && $user->save() && $email->save())
		{
			return response()->json(['msg' => 'new email set as primary'], 200);
		}
		else
		{
			return response()->json(['error' => 'could\'t set new email'], 404);
		}
	}

	public function destroy(Email $email)
	{
		if ($email->delete())
		{
			return response()->json(['msg' => 'email deleted successfully'], 202);
		}
	}
}
