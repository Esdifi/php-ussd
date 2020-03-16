<?php

namespace Dbilovd\PHUSSD\Traits;

use Dbilovd\PHUSSD\Requests\BaseRequest;
use Dbilovd\PHUSSD\Requests\HubtelRequest;
use Illuminate\Support\Facades\Config;

trait MakesUSSDRequestHandler
{
	/**
	 * Generate and return the Request Handler based on the configured
	 * default USSD service provider
	 * 
	 * @return \Dbilovd\PHUSSD\Contracts\Requests
	 */
	protected function makeRequest ()
	{
		$activeRequestConfig = Config("ussd.defaultServiceProvider");

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