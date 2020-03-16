<?php

namespace Dbilovd\PHUSSD\Pages;

class BillPaymentSaved extends BasePage
{
	/**
	 * Type of USSD Response given by this page.
	 * 
	 * @var string
	 */
	public $responseType = "end";

	/**
	 * Message string to return
	 * 
	 * @var String
	 */
	public $message = "Thank You\r\nThe payment has been submitted successfully";
}