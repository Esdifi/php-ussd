<?php

namespace Dbilovd\PHP_USSD\Factories;

use Dbilovd\PHP_USSD\Contracts\SessionManagersInterface;
use Dbilovd\PHP_USSD\SessionManagers\RedisSessionManager;

class SessionManagerFactory
{
	/**
	 * Make a new instace of a Session Manager
	 *
	 * @return \Dbilovd\PHP_USSD\Contracts\SessionManagersInterface
	 */
	public function make() : SessionManagersInterface
	{
		return new RedisSessionManager();
	}
}