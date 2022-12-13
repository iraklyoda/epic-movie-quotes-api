<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Geo_char;
use App\Rules\Eng_char;

class StoreMovieRequest extends FormRequest
{
	public function rules()
	{
		return [
			'image'          => ['required', 'image'],
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
