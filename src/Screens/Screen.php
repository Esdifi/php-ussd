<?php

namespace Esdifi\PHP_USSD\Screens;

use Esdifi\PHP_USSD\Contracts\ScreenContract;
use Esdifi\PHP_USSD\Factories\SessionManagerFactory;
use Esdifi\PHP_USSD\Managers\Configurations\Laravel as LaravelConfiguration;
use Esdifi\PHP_USSD\Traits\InteractsWithSession;

abstract class Screen implements ScreenContract
{
    use InteractsWithSession;

    /**
     * Gateway request.
     *
     * @var Esdifi\PHP_USSD\GatewayProviders\GatewayProviderRequestContract
     */
    public $gatewayRequest;

    /**
     * Gateway request.
     *
     * @var
     */
    public $sessionManager;

    /**
     * Default response type.
     *
     * @var string
     */
    public $responseType = 'continue';

    /**
     * Message string to return.
     *
     * @var string
     */
    public $message = "Hello there, \r\nthis is a USSD page.";

    /**
     * Next page class name.
     *
     * @var string
     */
    public $nextPage = false;

    /**
     * Previous page's user response.
     *
     * @var mixed
     */
    public $previousPagesUserResponse = false;

    /**
     * Constructor.
     *
     * @param null $previousPagesUserResponse
     */
    public function __construct($gatewayRequest, $previousPagesUserResponse = null)
    {
        $config = new LaravelConfiguration();
        $this->sessionManager = (new SessionManagerFactory($config))->make();
        $this->gatewayRequest = $gatewayRequest;
        $this->previousPagesUserResponse = $previousPagesUserResponse;

        $this->boot();
    }

    /**
     * Use this function to extend functionalities of a Page.
     *
     * @return void
     */
    protected function boot(): void
    {
    }

    /**
     * Return the key that will be used to store user response to this screen
     * By default this returns the $this->dateFieldKey
     * A Screen can overide this method to return a dynamic key.
     *
     * @return string|bool     Field key string or false if no key is set
     */
    public function dataFieldKey()
    {
        return $this->dataFieldKey ?? false;
    }

    /**
     * Returns a list of sub menus.
     *
     * @return array Child menus
     */
    public function menus()
    {
        return $this->menus ?: false;
    }

    /**
     * Check if User Response is valid.
     *
     * @return bool Response content
     */
    public function validUserResponse($userResponse)
    {
        if (! $userResponse) {
            return false;
        }

        if (property_exists($this, 'menus') && is_array($this->menus)) {
            return array_key_exists($userResponse, $this->menus);
        }

        return true;
    }

    /**
     * Return an instance of the next child class depending on the user input.
     *
     * @param string $userResponse
     * @return \Esdifi\PHP_USSD\Contracts\ScreenContract
     */
    public function next($userResponse)
    {
        return $this->nextPage ?: false;
    }

    /**
     * Prepare user response for storing.
     *
     * @return mixed Prepared user response for storing in DB
     */
    public function preparedUserResponse($userResponse)
    {
        return $userResponse;
    }

    /**
     * Save the Issue Title.
     *
     * @param string $userResponse The user's response to being presented this page
     * @return bool
     */
    public function save($userResponse, $sessionId)
    {
        $preparedUserResponse = $this->preparedUserResponse($userResponse);

        $keyToUseInSavingData = $this->dataFieldKey();
        if ($keyToUseInSavingData && $preparedUserResponse) {
            $existingData = json_decode('{}');

            if ($this->sessionManager->exists($sessionId, 'data')) {
                $existingData = $this->sessionManager->getValueOfSubKey($sessionId, 'data');
                $existingData = json_decode($existingData ?: '{}');
            }

            $existingData->{$keyToUseInSavingData} = $preparedUserResponse;
            $this->sessionManager->setValueOfSubKey(
                $sessionId,
                'data',
                json_encode($existingData)
            );
        }

        if (method_exists($this, 'fireEvents')) {
            $this->fireEvents($sessionId, $userResponse);
        }

        return true;
    }

    /**
     * Return the response type for this particular page per the request.
     *
     * @return mixed
     */
    public function responseType()
    {
        return $this->responseType ?: 'end';
    }

    /**
     * Return message to send back to client.
     *
     * @return string Response content
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Run this function before response is sent back to the user.
     *
     * @return void
     */
    public function beforeResponseHook()
    {
    }
}
