<?php

namespace Dbilovd\PHP_USSD\Traits;

use Dbilovd\PHP_USSD\Exceptions\InvalidOptionSelectedException;
use Dbilovd\PHP_USSD\Exceptions\InvalidScreenForNewSessionException;

trait ThrowsExceptions
{
    /**
     * Throw an Invalid Option Selected exception.
     *
     * @return void
     */
    protected function throwInvalidUserResponseException()
    {
        throw new InvalidOptionSelectedException('Invalid option selected');
    }

    /**
     * Throw an exception when a sub page is being requested for a new session.
     *
     * @return void
     */
    protected function throwInvalidScreenForNewSessionException()
    {
        throw new InvalidScreenForNewSessionException('Invalid option selected. No previous requests found.');
    }

    /**
     * Throw an exception when a save operation didn't work.
     *
     * @return void
     */
    protected function throwErrorWhileSavingResponseException()
    {
        throw new InvalidScreenForNewSessionException('An error occured while saving data.');
    }
}
