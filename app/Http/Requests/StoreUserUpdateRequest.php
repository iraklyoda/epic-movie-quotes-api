<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserUpdateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'profile_picture' => ['image'],
			'username'        => ['min:3'],
			'password'        => ['min:3', 'confirmed'],
		];
	}
}
