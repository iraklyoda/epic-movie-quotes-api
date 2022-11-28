<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	use HasFactory, HasTranslations;

	public $translatable = ['title', 'director', 'description'];

	protected $guarded = ['id'];

	public function quotes()
	{
		return $this->hasMany(Quote::class);
	}
}
