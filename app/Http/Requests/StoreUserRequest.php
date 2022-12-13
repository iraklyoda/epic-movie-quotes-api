<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
	public function rules()
	{
		return [
			'username'       => ['required', 'min:3', 'max:15', 'regex:/^[a-zA-Z0-9 ]+$/', 'unique:users'],
			'email'          => ['required', 'email', 'unique:users'],
			'password'       => ['required', 'confirmed'],
		];
	}
}
