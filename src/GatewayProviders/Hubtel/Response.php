<?php

namespace Dbilovd\PHUSSD\GatewayProviders\Hubtel;

use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderResponseContract;

class Response implements GatewayProviderResponseContract
{
	/**
	 * Response header content type
	 *
	 * @var string
	 */
	public $responseContentType = 'application/json';

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
				return "Release";
				break;

			case 'continue':
			default:
				return "Response";
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
	    $responseMessage = $page->message();

	    $responseType = $this->getResponseType(
	        $page->responseType ?: false
        );

		return json_encode([
            'Type' 		=> $responseType,
			'Message' 	=> $responseMessage
		]);
	}
}