<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailRequest;
use App\Models\email;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
	public function create(StoreEmailRequest $request)
	{
		Email::create([
			'email'   => $request->email,
			'user_id' => jwtUser()->id,
		])->sendEmailVerification(jwtUser()->id);
	}

	public function verify($email_id, Request $request)
	{
		if (!$request->hasValidSignature())
		{
			return response()->json(['msg' => 'Invalid/Expired url provided.'], 401);
		}
		$email = email::findOrFail($email_id);

		if (!$email->is_email_verified)
		{
			$email->is_email_verified = 1;
			$email->save();
		}
		return redirect(env('VITE_APP_ROOT') . '?email_verified=yes');
	}
}
