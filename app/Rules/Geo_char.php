<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Geo_char implements InvokableRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param string $attribute
	 * @param mixed  $value
	 * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 *
	 * @return void
	 */
	public function __invoke($attribute, $value, $fail)
	{
		$pattern = "/^[ა-ჰა-ჰ0-9_.,\-–—IVX&\n '()?:]+$/";
		if (!preg_match($pattern, $value))
		{
			$fail('The :attribute must be in georgian letters');
		}
	}
}
