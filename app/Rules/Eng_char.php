<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Eng_char implements InvokableRule
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
		$pattern = "/^[A-Za-z0-9_.,\-–—\n 'ō()?:ä\"]+$/";
		if (!preg_match($pattern, $value))
		{
			$fail('The :attribute must be in english letters');
		}
	}
}
