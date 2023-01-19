<?php

namespace Esdifi\PHP_USSD\GatewayProviders\Hubtel;

use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;

class Request implements GatewayProviderRequestContract
{
    /**
     * Response header content type.
     *
     * @var string
     */
    public $responseContentType = 'application/json';

    /**
     * Field name for Session ID field of current session.
     *
     * @var string
     */
    protected $sessionIdFieldName = 'SessionId';

    /**
     * Field name for User submitted USSD string.
     *
     * @var string
     */
    protected $ussdStringFieldName = 'Message';

    /**
     * Service Code.
     *
     * @var string
     */
    protected $serviceCodeFieldName = 'ServiceCode';

    /**
     * Phone number making request.
     *
     * @var string
     */
    protected $msisdnFieldName = 'Mobile';

    /**
     * Phone number making request.
     *
     * @var string
     */
    protected $networkFieldName = 'Operator';

    /**
     * Request Type field name.
     *
     * @var string
     */
    protected $requestTypeFieldName = 'Type';

    /**
     * HTTP Request object.
     *
     * @var
     */
    protected $httpRequest;

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
     * Get value of request.
     *
     * @return string Request type
     */
    public function getRequestType()
    {
        return $this->httpRequest->get($this->requestTypeFieldName);
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
        $ussdString = $this->getUSSDString();
        if (strpos($ussdString, '*') === false) {
            return $ussdString;
        }

        $responses = explode('*', $this->getUSSDString());

        return count($responses) > 1 ? $responses[count($responses) - 1] : false;
    }

    /**
     * Check if current request is a request to cancel session.
     *
     * @return bool True if this request is a cancellation request
     */
    public function isCancellationRequest()
    {
        return $this->getRequestType() == 'Release';
    }

    /**
     * Check if current request is the initial request.
     *
     * @return bool True if this request is the first request in session
     */
    public function isInitialRequest()
    {
        return $this->getRequestType() == 'Initiation';
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

    /**
     * Check if request is a Timeout request.
     *
     * @return bool Request received is a timeout request or not
     */
    public function isTimeoutRequest()
    {
        return $this->getRequestType() == 'Timeout';
    }

    /** TEMPORAL METHOD. TO BE REMOVED IN FAVOUR OF PROVIDER PROCESSOR KNOWING THEIR RESPONSE PROCESSOR CLASS */
    public function response($page)
    {
        return (new Response())->format($page);
    }
}
