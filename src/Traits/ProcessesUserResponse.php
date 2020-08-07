<?php

namespace Dbilovd\PHP_USSD\Traits;

trait ProcessesUserResponse
{
	/**
	 * [extractUserResponseFromUSSDString description]
	 * 
	 * @param  [type] $ussdString [description]
	 * @return [type]             [description]
	 */
	public function extractUserResponseFromUSSDString ($ussdString)
	{
		$responses = explode('*', $ussdString);
		return count($responses) > 1 ? $responses[ count($responses) - 1 ] : false;
	}
}