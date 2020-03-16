<?php

namespace Dbilovd\PHUSSD\Pages;

class Exception extends BasePage
{
	/**
	 * Message 
	 * 
	 * @var String
	 */
	public $message;

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
	public function responseType ()
	{
		return $this->request->getResponseType('end');
	}
}

