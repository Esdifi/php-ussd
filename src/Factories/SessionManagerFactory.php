<?php

namespace Dbilovd\PHUSSD\Factories;

use Dbilovd\PHUSSD\Contracts\SessionManagersInterface;
use Dbilovd\PHUSSD\SessionManagers\RedisSessionManager;

class SessionManagerFactory
{
	/**
	 * Make a new instace of a Session Manager
	 *
	 * @return \Dbilovd\PHUSSD\Contracts\SessionManagersInterface
	 */
	public function make() : SessionManagersInterface
	{
		return new RedisSessionManager();
	}
}