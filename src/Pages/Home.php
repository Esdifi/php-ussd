<?php

namespace Dbilovd\PHUSSD\Pages;

class Home extends BasePage
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
	protected $dataFieldKey = "homeOptionSelected";

	/**
	 * Menus within this page
	 * 
	 * @var Array
	 */
	protected $menus = [
		"1" => "Continue",
		"2" => "Cancel",
	];

	/**
	 * Return message to send back to client
	 *
	 * @return String Message to return to client
	 */
	public function message()
	{
		$menus = $this->menus();
		array_walk($menus, function(&$value, $key) {
			$value = "{$key}. $value";
		});
		$menus = implode("\r\n", $menus);
		return "Welcome to PHP USSD (PhUSSD) \r\n\r\n{$menus}";
	}

	/**
	 * Return an instance of the next child class depending on the user input
	 *
	 * @return \Dbilovd\PHUSSD\Contracts\Pages
	 */
	public function next($selectedOption)
	{
		$className = false;
		switch ($selectedOption) {
			case '1':
			case '2':
				$className = SearchForAnOwner::class;
				break;
		}
		
		return $className;
	}
}

