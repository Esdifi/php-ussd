<?php

namespace Esdifi\PHP_USSD\Screens;

class Exception extends Screen
{
    /**
     * Message.
     *
     * @var string
     */
    public $message;

    public $responseType = 'end';

    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Return message to send back to client.
     *
     * @return string Message to return to client
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Response type.
     *
     * @return mixed
     */
    public function getResponseType()
    {
        return 'end';
    }
}
