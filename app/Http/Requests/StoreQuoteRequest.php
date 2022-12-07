<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules()
	{
		return [
			'quote_en'    => 'required',
			'quote_ka'    => 'required',
			'movie_id'    => ['required', Rule::exists('movies', 'id')],
			'thumbnail'   => ['required', 'image'],
		];
	}
}
