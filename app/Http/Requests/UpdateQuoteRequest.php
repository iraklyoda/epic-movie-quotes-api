<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Geo_char;
use App\Rules\Eng_char;

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
			'thumbnail'          => ['image'],
			'quote_en'           => ['required', 'min:3', new Eng_char],
			'quote_ka'           => ['required', 'min:3', new Geo_char],
		];
	}
}
