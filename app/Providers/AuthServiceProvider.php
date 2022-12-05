<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Guards\JwtGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	public function boot()
	{
		$this->registerPolicies();

		Auth::extend('jwt', function ($app, $name, array $config) {
			return new JwtGuard(Auth::createUserProvider($config['provider']));
		});
	}
}
