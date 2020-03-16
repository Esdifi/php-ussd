<?php

namespace Dbilovd\PHUSSD\Factories;

use Dbilovd\PHUSSD\Contracts\SessionManagersContract;
use Dbilovd\PHUSSD\SessionManagers\RedisSessionManager;

class SessionManagerFactory
{
	/**
	 * Make a new instace of a Session Manager
	 *
	 * @return \Dbilovd\PHUSSD\Contracts\SessionManagersContract
	 */
	public function make() : SessionManagersContract
	{
		return new RedisSessionManager();
	}
}