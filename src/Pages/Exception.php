<?php

namespace Dbilovd\PHP_USSD\Pages;

class Exception extends BasePage
{
	/**
	 * Message 
	 * 
	 * @var String
	 */
	public $message;

	public $responseType = 'end';

	/**
	 * 
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * Return message to send back to client
	 *
	 * @return String Message to return to client
	 */
	public function message()
	{
		return $this->message;
	}

	/**
	 * Response type
	 * 
	 * @return Mixed
	 */
	public function getResponseType()
	{
		return 'end';
	}
}

