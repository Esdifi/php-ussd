<?php

namespace Dbilovd\PHUSSD\Pages;

class SearchForAnOwner extends BasePage
{
	/**
	 * Type of USSD Response given by this page.
	 * 
	 * @var string
	 */
	public $responseType = "continue";

	/**
	 * Session Data Field Key
	 * 
	 * @var string
	 */
	protected $dataFieldKey = "ownerSearchTerm";

	/**
	 * Message string to return
	 * 
	 * @var String
	 */
	public $message = "Enter the phone number to search for an owner, manager or caretaker.\r\n\r\n";

	/**
	 * Next page class name
	 * 
	 * @var String
	 */
	public $nextPage = SearchForAnOwnerResults::class;
}