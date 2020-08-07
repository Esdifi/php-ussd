<?php

use Dbilovd\PHP_USSD\Contracts\SessionManagersContract;
use Dbilovd\PHP_USSD\Factories\SessionManagerFactory;
use PHPUnit\Framework\TestCase;

class SessionManagerFactoryTest extends TestCase
{
	/** @test */
	public function it_makes_and_returns_a_class_that_implements_the_session_manager_contract()
	{
		$sessionManager = (new SessionManagerFactory)->make();

		$this->assertTrue($sessionManager instanceof SessionManagersContract);
	}
}