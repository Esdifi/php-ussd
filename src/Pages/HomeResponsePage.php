<?php


namespace Dbilovd\PHUSSD\Pages;


class HomeResponsePage extends BasePage
{
	/**
	 * Type of USSD Response given by this page.
	 *
	 * @var string
	 */
	public $responseType = "end";

	/**
	 * Return message to send back to client
	 *
	 * @return String Message to return to client
	 */
	public function message()
	{
		return "Done. You entered: {$this->previousPagesUserResponse}";
	}
}