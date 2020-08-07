<?php

namespace Dbilovd\PHUSSD\Helpers;

use Illuminate\Support\Facades\Config;

class Configurations
{
	/**
	 * Read and return the value of a config using key provided
	 *
	 * @param String $key 	Name of key for config value to fetch
	 * @return mixed 		Value of config value matching key provider
	 */
	public function getConfigValue($key)
	{
		if (! $key) {
			return false;
		}

		return Config($key);
	}
}