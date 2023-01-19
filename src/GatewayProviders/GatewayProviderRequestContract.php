<?php

namespace Esdifi\PHP_USSD\GatewayProviders;

interface GatewayProviderRequestContract
{
    /**
     * Fetch session ID for current request.
     *
     * @param bool $initialisingSession Default false
     * @return string Session ID
     */
    public function getSessionId($initialisingSession = false);

    /**
     * Fetch USSD string for current request.
     *
     * @return string USSD String
     */
    public function getUSSDString();

    /**
     * Fetch service code for current request.
     *
     * @return string Service Code
     */
    public function getServiceCode();

    /**
     * Fetch MSISDN for current request.
     *
     * @return string MSISDN
     */
    public function getMSISDN();

    /**
     * Check if request is a Timeout request.
     *
     * @return bool Request received is a timeout request or not
     */
    public function isTimeoutRequest();

    /**
     * Fetch and return User response from USSD string.
     *
     * @return string User Response string
     */
    public function getUserResponseFromUSSDString();
}
