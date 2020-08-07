<?php

use Dbilovd\PHP_USSD\Traits\ProcessesUserResponse;
use PHPUnit\Framework\TestCase;

class UserResponseProcessorsTest extends TestCase
{
	/** @test */
	public function it_extracts_a_user_response_from_a_full_ussd_string()
	{
		$ussdString = "123*yes";
		$userResponse = $this->getMockForTrait(ProcessesUserResponse::class)
			->extractUserResponseFromUSSDString($ussdString);

		$this->assertEquals("yes", $userResponse);
	}

	/** @test */
	public function it_returns_false_when_no_user_response_is_found_in_string()
	{
		$userResponse = $this->getMockForTrait(ProcessesUserResponse::class)
			->extractUserResponseFromUSSDString("123");

		$this->assertEquals(false, $userResponse);
	}
}
