<?php

namespace Esdifi\PHP_USSD\Services;

use Esdifi\PHP_USSD\Contracts\ScreenContract;
use Esdifi\PHP_USSD\Contracts\SessionManagersInterface;
use Esdifi\PHP_USSD\Factories\ScreensFactory;
use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderContract;
use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;
use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderResponseContract;
use Esdifi\PHP_USSD\Screens\Exception as ExceptionPage;
use Esdifi\PHP_USSD\Traits\InteractsWithSession;
use Esdifi\PHP_USSD\Traits\ProcessesUserResponse;
use Esdifi\PHP_USSD\Traits\ThrowsExceptions;
use Exception;

class CoreControllerService
{
    use InteractsWithSession,
        ProcessesUserResponse,
        ThrowsExceptions;

    /**
     * Session Manager.
     *
     * @var SessionManagersInterface
     */
    protected $sessionManager;

    /**
     * Name of class to use as initial page.
     *
     * @var string
     */
    public $initialPageClassName = \Esdifi\PHP_USSD\Screens\Home::class;

    /**
     * Gateway Request object.
     *
     * @var GatewayProviderRequestContract
     */
    private $gatewayRequest;

    /**
     * Gateway Response object.
     *
     * @var GatewayProviderResponseContract
     */
    private $gatewayResponse;

    /**
     * Configuration manager.
     *
     * @var
     */
    private $pagesFactoryManager;

    /**
     * Constructor.
     *
     * @param GatewayProviderContract $gatewayProvider
     * @param SessionManagersInterface $sessionManager
     * @param ScreensFactory $pagesFactoryManager
     */
    public function __construct(
        GatewayProviderContract $gatewayProvider,
        SessionManagersInterface $sessionManager,
        ScreensFactory $pagesFactoryManager
    ) {
        $this->gatewayRequest = $gatewayProvider->getRequest();
        $this->gatewayResponse = $gatewayProvider->getResponse();
        $this->sessionManager = $sessionManager;
        $this->pagesFactoryManager = $pagesFactoryManager;
    }

    /**
     * Handle HTTP requests to base endpoint.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        # - Generate Initialise USSD Gateway Request Processor based on config value
        */

        // - Checks if the request is valid, if not, throws exception
        // - Initialise Session manager to use for this particular request
        // - Initialise USSD session
        // - Based on Session and Gatway process, construct response to send back to client
        // - Format and return response

        $page = false;
        $exceptionType = false;

        try {
            if (! $this->gatewayRequest->isValidRequest()) {
                $exceptionType = 'invalid-request';
                throw new Exception('Error: Bad Request');
            }
            if ($this->gatewayRequest->isCancellationRequest()) {
                $exceptionType = 'cancelled';
                throw new Exception('You cancelled the request.');
            }
            if ($this->gatewayRequest->isTimeoutRequest()) {
                $exceptionType = 'timeout';
                throw new Exception('Timeout: You took too long to respond. Kindly try again.');
            }

            $this->initialiseSession();

            if ($this->gatewayRequest->isInitialRequest()) {
                $page = $this->pagesFactoryManager->make('initial');
                $this->sessionSetLastPage(get_class($page));
            }

            $page = $page ?: $this->constructResponsePage();

            if (! $page) {
                throw new Exception('Error: Could not construct new page.');
            }
        } catch (Exception $e) {
            $page = $this->pagesFactoryManager->make('exception');
            $page->setMessage($e->getMessage());
            
            if (! $this->gatewayRequest->isValidRequest()) {
                $page->exceptionType = 'invalid-request';
            }
            if ($this->gatewayRequest->isCancellationRequest()) {
                $page->exceptionType = 'cancelled';
            }
            if ($this->gatewayRequest->isTimeoutRequest()) {
                $page->exceptionType = 'timeout';
            }

            if (method_exists($page, 'fireEvents')) {
                $page->fireEvents($this->getSessionStoreIdString(), "");
            }
        }

        // Construct Response based on page to be returned
        $response = $this->gatewayResponse->format($page);

        if (method_exists($page, 'beforeResponseHook')) {
            $page->beforeResponseHook();
        }

        return response($response)
            ->header('Content-type', $this->gatewayResponse->responseContentType);
    }

    /**
     * Get response for this request.
     *
     * @return ScreenContract|bool Instance of page to return or false
     *
     * @throws Exception
     */
    protected function constructResponsePage()
    {
        $previousPageClass = $this->getLastPageForCurrentUserSession();
        if (! $previousPageClass) {
            return false;
        }

        $previousPage = new $previousPageClass($this->gatewayRequest);

        $userResponse = $this->gatewayRequest->getUserResponseFromUSSDString();
        $validUserResponse = $previousPage->validUserResponse($userResponse);
        if (! $validUserResponse) {
            $this->throwInvalidUserResponseException();
        }

        // Save Response before returning next screen
        $saved = $previousPage->save($userResponse, $this->getSessionStoreIdString());
        if (! $saved) {
            $this->throwErrorWhileSavingResponseException();
        }

        $page = $this->pagesFactoryManager->make('subsequent', $previousPage, $userResponse);

        if (! $page) {
            $this->throwInvalidUserResponseException();
        }

        $this->sessionSetLastPage(get_class($page));

        return $page;
    }
}
