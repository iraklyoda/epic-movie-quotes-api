<?php

namespace App\Http\Requests;

use App\Rules\Eng_char;
use App\Rules\Geo_char;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules()
	{
		return [
			'image'          => ['image'],
			'genres'         => ['required'],
			'title_en'       => ['required', 'min:3', 'max:30', new Eng_char],
			'title_ka'       => ['required', 'min:3', 'max:30', new Geo_char],
			'director_en'    => ['required', 'min:3', 'max:30', new Eng_char],
			'director_ka'    => ['required', 'min:3', 'max:30', new Geo_char],
			'description_en' => ['required', 'min:3', new Eng_char],
			'description_ka' => ['required', 'min:3', new Geo_char],
		];
	}
}
