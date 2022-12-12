<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\Geo_char;
use App\Rules\Eng_char;

class StoreQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules()
	{
		return [
			'quote_en'    => ['required', 'min:3', new Eng_char],
			'quote_ka'    => ['required', 'min:3', new Geo_char],
			'movie_id'    => ['required', Rule::exists('movies', 'id')],
			'thumbnail'   => ['required', 'image'],
		];
	}
}
