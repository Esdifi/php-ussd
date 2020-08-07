<?php

namespace Dbilovd\PHUSSD\Services;

use Dbilovd\PHUSSD\Contracts\Pages;
use Dbilovd\PHUSSD\Contracts\SessionManagersInterface;
use Dbilovd\PHUSSD\Factories\PagesFactory;
use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderContract;
use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderRequestContract;
use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderResponseContract;
use Dbilovd\PHUSSD\Pages\Exception as ExceptionPage;
use Dbilovd\PHUSSD\Traits\InteractsWithSession;
use Dbilovd\PHUSSD\Traits\ProcessesUserResponse;
use Dbilovd\PHUSSD\Traits\ThrowsExceptions;
use Exception;

class CoreControllerService
{
	use InteractsWithSession,
		ProcessesUserResponse,
		ThrowsExceptions;
	
	/**
	 * Session Manager
	 * 	
	 * @var SessionManagersInterface
	 */
	protected $sessionManager;

	/**
	 * Name of class to use as initial page
	 * 
	 * @var String
	 */
	public $initialPageClassName = \Dbilovd\PHUSSD\Pages\Home::class;

    /**
     * Gateway Request object
     *
     * @var GatewayProviderRequestContract
     */
    private $gatewayRequest;

    /**
     * Gateway Response object
     *
     * @var GatewayProviderResponseContract
     */
    private $gatewayResponse;

    /**
     * Constructor
     *
     * @param GatewayProviderContract $gatewayProvider
     * @param SessionManagersInterface $sessionManager
     */
	public function __construct(GatewayProviderContract $gatewayProvider, SessionManagersInterface $sessionManager)
	{
		$this->gatewayRequest = $gatewayProvider->getRequest();
		$this->gatewayResponse = $gatewayProvider->getResponse();
		$this->sessionManager = $sessionManager;
	}
	
	/**
	 * Handle HTTP requests to base endpoint
	 *
	 * @return mixed
	 */
	public function handle()
	{
		/* 
		# - Generate Initialise USSD Gateway Request Processor based on config value 
		*/
	
		# - Checks if the request is valid, if not, throws exception
		# - Initialise Session manager to use for this particular request
		# - Initialise USSD session
		# - Based on Session and Gatway process, construct response to send back to client
		# - Format and return response

        $page = false;

		try {
            if (!$this->gatewayRequest->isValidRequest()) {
                throw new Exception("Error: Bad Request");
            }
            if ($this->gatewayRequest->isCancellationRequest()) {
                throw new Exception("You cancelled the request.");
            }
            if ($this->gatewayRequest->isTimeoutRequest()) {
                throw new Exception("Timeout: You took too long to respond. Kindly try again.");
            }

            if ($this->gatewayRequest->isInitialRequest()) {
                $pageFactory = (new PagesFactory($this->gatewayRequest));
                $pageFactory->setInitialPageClass($this->initialPageClassName);
                $page = $pageFactory->make('initial');
            }

            $this->initialiseSession();

            $page = $page ?: $this->constructResponsePage();

		} catch (Exception $e) {
			$page = new ExceptionPage($this->gatewayRequest);
			$page->setMessage($e->getMessage());
		}

		// Construct Response based on page to be returned
        $response = $this->gatewayResponse->format($page);

		return response($response)
			->header('Content-type', $this->gatewayResponse->responseContentType);
	}

	/**
	 * Get response for this request
	 *
	 * @return Pages Instance of page to return
     *
     * @throws Exception
	 */
	protected function constructResponsePage()
	{
		$page = false;

		$lastPageClass = $this->getLastPageForCurrentUserSession();
		$lastPage = null;
		if ($lastPageClass) {
		    $lastPage = new $lastPageClass($this->gatewayRequest);
		}

		$userResponse = $this->gatewayRequest->getUserResponse();
		$validUserResponse = $lastPage->validUserResponse($userResponse);
		if (! $validUserResponse) {
				$this->throwInvalidUserResponseException();
			}

		// Save Response before returning next screen
        $saved = $lastPage->save($userResponse, $this->getSessionStoreIdString());
		if (! $saved) {
		    $this->throwErrorWhileSavingResponseException();
		}

		$page = (new PagesFactory($this->gatewayRequest))
            ->make('subsequent', $lastPage, $userResponse);

		if (!$page) {
			$this->throwInvalidUserResponseException();
		}

		$this->sessionSetLastPage(get_class($page));

		return $page;
	}
}