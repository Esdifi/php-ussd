<?php

namespace Dbilovd\PHP_USSD\Traits;

trait InteractsWithSession
{
    /**
     * Construct and return session id used in store.
     *
     * @return string Fully constructed session ID
     */
    protected function getSessionStoreIdString(): string
    {
        return "ussd_session_{$this->gatewayRequest->getSessionId()}";
    }

    /**
     * Initialises a USSD session.
     *
     * @return void
     */
    protected function initialiseSession()
    {
        $sessionStoreId = $this->getSessionStoreIdString();
        if (! $this->sessionManager->exists($sessionStoreId)) {
            $this->sessionManager->setValueOfSubKey($sessionStoreId, 'initialised', time());
            $this->sessionManager->setValueOfSubKey($sessionStoreId, 'phone', $this->gatewayRequest->getMSISDN());
            $this->sessionManager->setValueOfSubKey($sessionStoreId, 'network', $this->gatewayRequest->getNetwork());
        }
    }

    /**
     * Fetch session data.
     *
     * @return mixed all data for current session
     */
    protected function fetchSession()
    {
        $this->initialiseSession();

        return $this->sessionManager->getValueOfKey($this->getSessionStoreIdString());
    }

    /**
     * Update the last page that was returned for a session.
     *
     * @param  string $value Name of Page class to set as most recent page
     * @return void
     */
    protected function sessionSetLastPage($value)
    {
        $this->sessionManager->setValueOfSubKey($this->getSessionStoreIdString(), 'lastPage', $value);
    }

    /**
     * Return the class name of the most recent Page.
     *
     * @return string Class name of most recent Page class
     */
    protected function getLastPageForCurrentUserSession()
    {
        $sessionStoreId = $this->getSessionStoreIdString();
        if (! $this->sessionManager->exists($sessionStoreId, 'lastPage')) {
            $this->throwInvalidScreenForNewSessionException();
        }

        return $this->sessionManager->getValueOfSubKey($sessionStoreId, 'lastPage');
    }
}
