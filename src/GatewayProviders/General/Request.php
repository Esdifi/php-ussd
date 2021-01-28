<?php

namespace Dbilovd\PHP_USSD\GatewayProviders\General;

use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;
use Dbilovd\PHP_USSD\GatewayProviders\General\Response as GeneralProviderResponse;

class Request implements GatewayProviderRequestContract
{
    /**
     * HTTP Request object.
     *
     * @var
     */
    protected $httpRequest;

    /**
     * Field name for Session ID field of current session.
     *
     * @var string
     */
    protected $sessionIdFieldName = 'sessionId';

    /**
     * Field name for User submitted USSD string.
     *
     * @var string
     */
    protected $ussdStringFieldName = 'ussdString';

    /**
     * Service Code.
     *
     * @var string
     */
    protected $serviceCodeFieldName = 'serviceCode';

    /**
     * Phone number making request.
     *
     * @var string
     */
    protected $msisdnFieldName = 'msisdn';

    /**
     * Network making request.
     *
     * @var bool
     */
    protected $networkFieldName = false;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct($httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * Fetch session ID for current request.
     *
     * @param bool $initialisingSession Default false
     * @return string Session ID
     */
    public function getSessionId($initialisingSession = false)
    {
        return $this->httpRequest->get($this->sessionIdFieldName);
    }

    /**
     * Fetch USSD string for current request.
     *
     * @return string USSD String
     */
    public function getUSSDString()
    {
        return $this->httpRequest->get($this->ussdStringFieldName);
    }

    /**
     * Fetch service code for current request.
     *
     * @return string Service Code
     */
    public function getServiceCode()
    {
        return $this->httpRequest->get($this->serviceCodeFieldName);
    }

    /**
     * Fetch MSISDN for current request.
     *
     * @return string MSISDN
     */
    public function getMSISDN()
    {
        return $this->httpRequest->get($this->msisdnFieldName);
    }

    /**
     * Fetch Network used for current request.
     *
     * @return string Network handle
     */
    public function getNetwork()
    {
        return $this->networkFieldName ?
            $this->httpRequest->get($this->networkFieldName) : false;
    }

    /**
     * Fetch and return User response from USSD string.
     *
     * @return string User Response string
     */
    public function getUserResponseFromUSSDString()
    {
        $responses = explode('*', $this->getUSSDString());

        return count($responses) > 1 ? $responses[count($responses) - 1] : false;
    }

    /**
     * Check if current request is the initial request.
     *
     * @return bool True if this request is the first request in session
     */
    public function isInitialRequest(): bool
    {
        return $this->getUSSDString() == '';
    }

    /**
     * Check if current request is a request to cancel session.
     *
     * @todo
     *
     * @return bool True if this request is a cancellation request
     */
    public function isCancellationRequest(): bool
    {
        return false;
    }

    /**
     * Check if request is a Timeout request.
     *
     * @todo
     *
     * @return bool Request received is a timeout request or not
     */
    public function isTimeoutRequest(): bool
    {
        return false;
    }

    /**
     * Check if submitted request is valid.
     *
     * @return bool True if request is valid, False if not
     */
    public function isValidRequest()
    {
        return ! empty($this->getMSISDN()) && ! empty($this->getServiceCode());
    }

    /** TEMPORAL METHOD. TO BE REMOVED IN FAVOUR OF PROVIDER PROCESSOR KNOWING THEIR RESPONSE PROCESSOR CLASS */
    public function response($page)
    {
        return (new GeneralProviderResponse())->format($page);
    }
}
