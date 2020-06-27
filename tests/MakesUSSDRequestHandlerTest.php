<?php

use Dbilovd\PHUSSD\Contracts\Requests;
use Dbilovd\PHUSSD\Helpers\Configuarations;
use Dbilovd\PHUSSD\Traits\MakesUSSDRequestHandler;
use PHPUnit\Framework\TestCase;

class MakesUSSDRequestHandlerTest extends TestCase
{
	/** @test */
	public function calling_makeRequest_returns_an_instance_of_USSDRequest_handler()
	{
		$mock = $this->getMockBuilder(Configuarations::class)
			->setMethods(['getConfigValue'])
			->getMock();

		$mock->expects($this->once())
			->method('getConfigValue')
			->will($this->returnValue('general'));
		
		$requestHandler = $this->getMockForTrait(MakesUSSDRequestHandler::class)->makeRequest($mock);

		$this->assertTrue($requestHandler instanceof Requests);
	}
}
