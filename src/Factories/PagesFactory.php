<?php

namespace Dbilovd\PHUSSD\Factories;

use Dbilovd\PHUSSD\Contracts\Pages;
use Dbilovd\PHUSSD\Pages\Home;
use Dbilovd\PHUSSD\Traits\InteractsWithSession;

class PagesFactory
{
	use InteractsWithSession;

	/**
	 * Request object
	 * 
	 * @var [type]
	 */
	protected $request;

	/**
	 * Class name of page
	 * 
	 * @var String
	 */
	protected $initialPageClass;

	/**
	 * Constructor
	 *
	 */
	public function __construct($request)
	{
		$this->request = $request;
	}

    /**
     * Make and return a USSD Page
     *
     * @param $type
     * @param Pages|null $previousPage
     * @param null $userResponse
     * @return Pages A class that implements the Pages contract
     */
	public function make($type, Pages $previousPage = null, $userResponse = null) : Pages
	{
		switch ($type) {
			case 'subsequent':
				return $this->subsequentPages($previousPage, $userResponse);
				break;

			case 'initial':
			default:
				return $this->initialPage();
				break;
		}
	}

	/**
	 * Instantiate and return the initial page
	 *
	 * @return Pages
	 */
	protected function initialPage(): Pages
	{
	    $initialPageClassName = $this->getInitialPageClass();
		return (new $initialPageClassName($this->request));
	}

    /**
     * Handle requests that are not the initial, cancellation or timeout requests
     *
     * @param Pages $previousPage
     * @param $userResponse
     * @return Pages Response string
     */
	protected function subsequentPages(Pages $previousPage, $userResponse): Pages
	{
		$nextPageClassName = $previousPage->next($userResponse);
		
		if (! $nextPageClassName) {
			$this->throwInvalidUserResponseException();
		}

		return (new $nextPageClassName($this->request, $userResponse));
	}

	/**
	 * Set the class name of the initial page
	 *
	 * @param String $className
	 * @return void
	 */
	public function setInitialPageClass(String $className) : void
	{
		$this->initialPageClass = $className;
	}

    /**
     *
     *
     * @return String
     */
    public function getInitialPageClass(): String
    {
        return $this->initialPageClass;
    }
}