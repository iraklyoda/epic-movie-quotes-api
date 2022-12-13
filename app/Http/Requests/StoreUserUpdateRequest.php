<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserUpdateRequest extends FormRequest
{
	public function rules()
	{
		return [
			'profile_picture'     => ['image'],
			'username'            => ['min:3', 'unique:users'],
			'new_password'        => ['min:3', 'confirmed'],
		];
	}
}
