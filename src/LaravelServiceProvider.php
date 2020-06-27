<?php

namespace Dbilovd\PHUSSD;

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
			__DIR__ . '/config/laravel.php'	=> config_path('phussd.php'),
		]);
	}

	/**
	 * Service Provide Register Method
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/config/laravel.php', 'phussd.php'
		);
	}
}