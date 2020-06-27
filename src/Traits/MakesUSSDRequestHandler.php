<?php

namespace Dbilovd\PHUSSD\Traits;

use Dbilovd\PHUSSD\Helpers\Configuarations;
use Dbilovd\PHUSSD\Requests\BaseRequest;
use Dbilovd\PHUSSD\Requests\HubtelRequest;

trait MakesUSSDRequestHandler
{
	/**
	 * Generate and return the Request Handler based on the configured
	 * default USSD service provider
	 * 
	 * @return \Dbilovd\PHUSSD\Contracts\Requests
	 */
	public function makeRequest (Configuarations $config)
	{
		$activeRequestConfig = $config->getConfigValue("phussd.defaultServiceProvider");

		$requestClass = false;
		switch (strtolower($activeRequestConfig)) {
			case 'hubtel':
				$requestClass = new HubtelRequest();
				break;

			case 'general':
			default:
				$requestClass = new BaseRequest();
				break;
		}

		return $requestClass;
	}
}