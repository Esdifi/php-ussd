<?php

namespace Dbilovd\PHP_USSD\Screens;

class HomeResponsePage extends Screen
{
    /**
     * Type of USSD Response given by this page.
     *
     * @var string
     */
    public $responseType = 'end';

    /**
     * Return message to send back to client.
     *
     * @return string Message to return to client
     */
    public function message()
    {
        return "Done. You entered: {$this->previousPagesUserResponse}";
    }
}
