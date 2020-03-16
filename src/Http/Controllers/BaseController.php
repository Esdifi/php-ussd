<?php

namespace Dbilovd\PHUSSD\Http\Controllers;

use App\Http\Controllers\Controller;
use Dbilovd\PHUSSD\Contracts\SessionManagersContract;
use Dbilovd\PHUSSD\Factories\SessionManagerFactory;
use Dbilovd\PHUSSD\Pages\Exception 	as ExceptionPage;
use Dbilovd\PHUSSD\Pages\Home 			as HomePage;
use Dbilovd\PHUSSD\Traits\MakesSessionManagers;
use Dbilovd\PHUSSD\Traits\MakesUSSDRequestHandler;
use Exception;
use Illuminate\Http\Request;

class BaseController extends Controller
{
	use MakesUSSDRequestHandler,
		MakesSessionManagers;
	
	/**
	 * Request object
	 * 	
	 * @var String
	 */
	protected $request;
	
	/**
	 * Session Manager
	 * 	
	 * @var \Dbilovd\PHUSSD\Contracts\SessionManagersContract
	 */
	protected $sessionManager;

	/**
	 * Home endpoint for all USSD requests
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function home(Request $request)
	{
		try {
			$this->request = $this->makeRequest();
			$this->sessionManager = $this->makeSessionManager();

			if (! $this->request->isValidRequest()) {
				throw new Exception("Bad Request");
			}

			$this->initialiseSession();

			$response = $this->constructResponse();

		} catch (Exception $e) {
			$exceptionPage = new ExceptionPage($this->request);
			$exceptionPage->setMessage("An Error Occurred: {$e->getMessage()}");
			$response = $exceptionPage->response();
		}

		return response($response)
			->header('Content-type', $this->request->responseContentType);
	}

	/**
	 * Initialises a USSD session
	 * 	
	 * @return void
	 */
	protected function initialiseSession ()
	{
		$sessionStoreId = $this->getSessionStoreIdString();
		if (! $this->sessionManager->exists($sessionStoreId)) {
			$this->sessionManager->setValueOfSubKey($sessionStoreId, "initialised", time());
			$this->sessionManager->setValueOfSubKey($sessionStoreId, "phone", $this->request->getMSISDN());
			$this->sessionManager->setValueOfSubKey($sessionStoreId, "network", $this->request->getNetwork());
		}
	}

	/**
	 * [fetchSession description]
	 * 
	 * @return [type] [description]
	 */
	protected function fetchSession ()
	{
		$this->initialiseSession();
		return $this->sessionManager->getValueOfKey($this->getSessionStoreIdString());
	}

	/**
	 * Construct and return session id used in store
	 * 
	 * @return string Fully constructed session ID
	 */
	protected function getSessionStoreIdString () : string
	{
		return "ussd_session_{$this->request->getSessionId()}";
	}

	/**
	 * Throw an Invalid Option Selected exception.
	 *
	 * @return void 
	 */
	protected function throwInvalidUserResponseException()
	{
		throw new InvalidOptionSelectedException("Invalid option selected");
	}

	/**
	 * Throw an exception when a sub page is being requested for a new session.
	 *
	 * @return void 
	 */
	protected function throwInvalidPageForNewSessionException()
	{
		throw new InvalidPageForNewSessionException("Invalid option selected. No previous requests found.");
	}

	/**
	 * Throw an exception when a save operation didn't work
	 *
	 * @return void 
	 */
	protected function throwErrorWhileSavingResponseException()
	{
		throw new InvalidPageForNewSessionException("An error occured while saving data.");
	}

	/**
	 * [sessionSetLastPage description]
	 * 
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	protected function sessionSetLastPage ($value)
	{
		$this->sessionManager->setValueOfSubKey($this->getSessionStoreIdString(), "lastPage", $value);
	}


	/**
	 * Get response for this request
	 *
	 * @return String Response string to return
	 */
	protected function constructResponse()
	{
		$response = false;
		if ($this->request->isCancellationRequest()) {
			throw new Exception("You cancelled the request.");
		} elseif ($this->request->isTimeoutRequest()) {
			throw new Exception("You took too long to respond. Kindly try again.");
		} elseif ($this->request->isInitialRequest()) {
			$response = $this->initialPage();
		} else {
			$response = $this->subsequentPages();
		}

		if (! $response) {
			$this->throwInvalidUserResponseException();
		}

		return $response;
	}

	/**
	 * Return initial page
	 * 
	 * @return [type] [description]
	 */
	protected function initialPage ()
	{
		$page = (new HomePage($this->request));
		$this->sessionSetLastPage(get_class($page));
		return $page->response();
	}

	/**
	 * Handle requests that are not the initial, cancellation or timeout requests
	 *
	 * @return String Response string
	 */
	protected function subsequentPages()
	{
		$lastPageClass = $this->getLastPageForCurrentUserSession();
	    $lastPage = new $lastPageClass($this->request);
		if (! $lastPage) {
		}

		$userResponse = $this->extractUserResponseFromUSSDString($this->request->getUSSDString());
		$validUserResponse = $lastPage->validUserResponse($userResponse);
		if (! $validUserResponse) {
			$this->throwInvalidUserResponseException();
		}

		// Save Response before returning next screen
		$saved = $lastPage->save($userResponse, $this->getSessionStoreIdString());
		if (! $saved) {
			$this->throwErrorWhileSavingResponseException();
		}

		$nextPageClassName = $lastPage->next($userResponse);
		if (! $nextPageClassName) {
			$this->throwInvalidUserResponseException();
		}

		$nextPage = new $nextPageClassName($this->request);
		$this->sessionSetLastPage(get_class($nextPage));
		return $nextPage->response();
	}

	/**
	 * [getLastPageForCurrentUserSession description]
	 * @return [type] [description]
	 */
	protected function getLastPageForCurrentUserSession ()
	{
		$sessionStoreId = $this->getSessionStoreIdString();
		if (! $this->sessionManager->exists($sessionStoreId, "lastPage")) {
			$this->throwInvalidPageForNewSessionException();
		}

		return $this->sessionManager->getValueOfSubKey($sessionStoreId, "lastPage");
	}

	/**
	 * [extractUserResponseFromUSSDString description]
	 * @param  [type] $ussdString [description]
	 * @return [type]             [description]
	 */
	protected function extractUserResponseFromUSSDString ($ussdString)
	{
		$responses = explode('*', $ussdString);
		return count($responses) > 0 ? $responses[ count($responses) - 1 ] : '';
	}

}