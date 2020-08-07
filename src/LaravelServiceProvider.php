<?php

namespace Dbilovd\PHP_USSD;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
	/**
	 * Service Provider boot
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/laravel.php'	=> config_path('php-ussd.php'),
		], 'config');
	}

	/**
	 * Service Provide Register Method
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/config/laravel.php', 'php-ussd.php'
		);
	}
}