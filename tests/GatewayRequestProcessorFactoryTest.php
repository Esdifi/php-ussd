<?php

use Dbilovd\PHP_USSD\Contracts\ConfigurationManagerInterface;
use Dbilovd\PHP_USSD\Factories\GatewayRequestProcessorFactory;
use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderContract;
use Dbilovd\PHP_USSD\Helpers\Configuarations;
use Dbilovd\PHP_USSD\Helpers\HttpRequest;
use Dbilovd\PHP_USSD\GatewayRequestProcessors\DefaultRequestProcessor;
use Dbilovd\PHP_USSD\Traits\MakesUSSDRequestHandler;
use PHPUnit\Framework\TestCase;

class GatewayRequestProcessorFactoryTest extends TestCase
{
	/** @test */
	public function calling_makeRequest_returns_an_instance_of_USSDRequest_handler()
	{
		$configurationMock = $this->createMock(ConfigurationManagerInterface::class);
			// ->setMethods(['getConfigValue'])
			// ->getMock();

		// $configurationMock->expects($this->once())
		// 	->method('getConfigValue')
		// 	->will($this->returnValue('general'));

		$httpRequestMock = $this->getMockBuilder(HttpRequest::class)
            ->setMethods(['getRequest', 'getResponse'])
            ->getMock();
		
		$requestHandler = (new GatewayRequestProcessorFactory($configurationMock))
			->make($httpRequestMock);

		$this->assertTrue($requestHandler instanceof GatewayProviderContract);
	}
}
