<?php

namespace Dbilovd\PHP_USSD\Screens;

use Dbilovd\PHP_USSD\Contracts\ScreenContract;
use Illuminate\Support\Facades\Redis;

abstract class Screen implements ScreenContract
{
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
    public function __construct($previousPagesUserResponse = null)
    {
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
     * @return \Dbilovd\PHP_USSD\Contracts\ScreenContract
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

        if (property_exists($this, 'dataFieldKey') && $preparedUserResponse) {
            $existingData = json_decode('{}');
            if (Redis::hExists($sessionId, 'data')) {
                $existingData = json_decode(
                    Redis::hGet($sessionId, 'data')
                );
            }

            $existingData->{$this->dataFieldKey} = $preparedUserResponse;

            Redis::hSet($sessionId, 'data', json_encode($existingData));
        }

        if (method_exists($this, 'fireEvents')) {
            $this->fireEvents($sessionId);
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
}
