<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailRequest;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
	public function create(StoreEmailRequest $request)
	{
		$email = Email::create([
			'email'   => $request->email,
			'user_id' => jwtUser()->id,
		]);
		jwtUser()->sendEmailVerification($email->id);
	}

	public function verify($email_id, Request $request)
	{
		if (!$request->hasValidSignature())
		{
			return response()->json(['msg' => 'Invalid/Expired url provided.'], 401);
		}
		$email = Email::findOrFail($email_id);

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
			return response()->json(['email deleted successfully'], 200);
		}
		else
		{
			return response()->json(['Email deleting film'], 404);
		}
	}
}
