<?php

namespace Esdifi\PHP_USSD\Contracts;

interface ScreenContract
{
    /**
     * Returns a list of sub menus.
     *
     * @return array Child menus
     */
    public function menus();

    /**
     * Return the type of response to be sent.
     *
     * @return string Response content
     */
    public function responseType();

    /**
     * Return message to send back to client.
     *
     * @return string Response content
     */
    public function message();

    /**
     * Return an instance of the next child class depending on the user input.
     *
     * @param string $userResponse The user's response to being presented this screen
     * @return ScreenContract
     */
    public function next($userResponse);

    /**
     * Save the user response.
     *
     * @param string $userResponse The user's response to being presented this screen
     * @param $sessionId
     * @return bool
     */
    public function save($userResponse, $sessionId);

    /**
     * Check if User Response is valid.
     *
     * @param string 	$userResponse Response provided by the user
     * @return bool 	Response content
     */
    public function validUserResponse($userResponse);

    /**
     * Return the key that will be used to store user response to this screen
     * By default this returns the $this->dateFieldKey
     * A Screen can overide this method to return a dynamic key.
     *
     * @return string|bool     Field key string or false if no key is set
     */
    public function dataFieldKey();

    /**
     * Run this function before response is sent back to the user.
     *
     * @return void
     */
    public function beforeResponseHook();
}
