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
		$email = Email::findOrFail($email_id);

		if (!$email->is_email_verified)
		{
			$email->is_email_verified = 1;
			$email->save();
		}
		return redirect(env('VITE_APP_ROOT') . '?email_verified=yes');
	}
}
