<?php

namespace Dbilovd\PHUSSD\Traits;

use Dbilovd\PHUSSD\Exceptions\InvalidOptionSelectedException;
use Dbilovd\PHUSSD\Exceptions\InvalidPageForNewSessionException;

trait ThrowsExceptions
{
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
}