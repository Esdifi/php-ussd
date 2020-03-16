<?php

namespace Dbilovd\PHUSSD\Traits;

use Dbilovd\PHUSSD\Contracts\SessionManagersContract;
use Dbilovd\PHUSSD\Factories\SessionManagerFactory;

trait MakesSessionManagers
{
	/**
	 * Instantiate and return a session manager
	 *
	 * @return \Dbilovd\PHUSSD\Contracts\SessionManagersContract
	 */
	public function makeSessionManager() : SessionManagersContract
	{
		return (new SessionManagerFactory())->make();
	}
}