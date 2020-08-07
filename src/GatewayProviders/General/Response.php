<?php

namespace Dbilovd\PHUSSD\GatewayProviders\General;

use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderResponseContract;

class Response implements GatewayProviderResponseContract
{
	/**
	 * Response header content type
	 *
	 * @var string
	 */
	public $responseContentType = 'text/plain';

    /**
     *
     *
     * @param string $type
     * @return string
     */
	public function getResponseType($type): string
	{
		switch ($type) {
			case 'end':
				return "END";
				break;

			case 'continue':
			default:
				return "CON";
				break;
		}
	}

	/**
	 * Format response to be sent to gateway provider
	 *
	 * @return string
	 */
	public function format($page): string
	{
	    $responseType = $this->getResponseType(
	        $page->responseType ?: false
        );
	    $responseMessage = $page->message();

		return "{$responseType} {$responseMessage}";
	}
}