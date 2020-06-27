<?php

namespace Dbilovd\PHUSSD\Http\Controllers;

use App\Http\Controllers\Controller;
use Dbilovd\PHUSSD\Contracts\SessionManagersContract;
use Dbilovd\PHUSSD\Factories\PagesFactory;
use Dbilovd\PHUSSD\Factories\SessionManagerFactory;
use Dbilovd\PHUSSD\Pages\Exception 	as ExceptionPage;
use Dbilovd\PHUSSD\Traits\InteractsWithSession;
use Dbilovd\PHUSSD\Traits\MakesSessionManagers;
use Dbilovd\PHUSSD\Traits\MakesUSSDRequestHandler;
use Dbilovd\PHUSSD\Traits\ProcessesUserResponse;
use Dbilovd\PHUSSD\Traits\ThrowsExceptions;
use Exception;
use Illuminate\Http\Request;

class BaseController extends Controller
{
	use InteractsWithSession,
		MakesUSSDRequestHandler,
		MakesSessionManagers,
		ProcessesUserResponse,
		ThrowsExceptions;
	
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
	 * Name of class to use as initial page
	 * 
	 * @var String
	 */
	public $initialPageClassName = \Dbilovd\PHUSSD\Pages\Home::class;

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
			$pageFactory = (new PagesFactory($this->request));
			$pageFactory->setInitialPageClass($this->initialPageClassName);
			$page = $pageFactory->make('initial');
		} else {
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

			$page = (new PagesFactory($this->request))->make('subsequent', $lastPage, $userResponse);
		}

		if ($page) {
			$this->sessionSetLastPage(get_class($page));
			$response = $page->response();
		}

		if (! $response) {
			$this->throwInvalidUserResponseException();
		}

		return $response;
	}
}