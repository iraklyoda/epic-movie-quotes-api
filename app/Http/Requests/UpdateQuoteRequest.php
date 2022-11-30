<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'thumbnail'          => 'image',
			'quote_en'           => 'required',
			'quote_ka'           => 'required',
			'movie_id'           => ['required', Rule::exists('movies', 'id')],
		];
	}
}
