<?php

namespace Dbilovd\PHUSSD\Managers\Configurations;

class Laravel implements ConfigurationManagerContract
{
	/**
	 * Get value of configuration option
	 *
	 * @param string $key 	Name of configuration key
	 * @return mixed 		Value of configuration option
	 */
	public function get(string $key)
	{
		return Config($key);
	}

}
