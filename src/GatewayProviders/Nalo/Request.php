<?php

namespace Esdifi\PHP_USSD\GatewayProviders\Nalo;

use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;
use Esdifi\PHP_USSD\Traits\InteractsWithSession;

class Request implements GatewayProviderRequestContract
{
    use InteractsWithSession;

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
    protected $sessionIdFieldName = 'USERID';

    /**
     * Field name for User submitted USSD string.
     *
     * @var string
     */
    protected $ussdStringFieldName = 'USERDATA';

    /**
     * Service Code.
     *
     * @var string
     */
    protected $serviceCodeFieldName = false;

    /**
     * Phone number making request.
     *
     * @var string
     */
    protected $msisdnFieldName = 'MSISDN';

    /**
     * Phone number making request.
     *
     * @var string
     */
    protected $networkFieldName = 'NETWORK';

    /**
     * Request Type field name.
     *
     * @var string
     */
    protected $requestTypeFieldName = 'MSGTYPE';

    /**
     * HTTP Request object.
     *
     * @var
     */
    protected $httpRequest;

    /**
     * Session manager.
     *
     * @var
     */
    protected $sessionManager;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct($httpRequest, $sessionManager)
    {
        $this->httpRequest = $httpRequest;
        $this->sessionManager = $sessionManager;
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
        if ($initialisingSession && $this->isInitialRequest()) {
            $this->sessionManager->setValueOfSubKey(
                'nalo_session_ids',
                $this->getMSISDN(),
                $this->generateUniqueStringForSessionId()
            );
        }

        $sessionId = $this->sessionManager->getValueOfSubKey(
            'nalo_session_ids',
            $this->getMSISDN(),
        );

        return "nalo_{$this->getMSISDN()}_{$sessionId}";
    }

    /**
     * Fetch value of the session field.
     * This is useful since Nalo's session ID will be generated at our end.
     *
     * @return string USSD String
     */
    public function getSessionFieldValue()
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
        return false;
    }

    /**
     * Check if current request is the initial request.
     *
     * @return bool True if this request is the first request in session
     */
    public function isInitialRequest()
    {
        return $this->getRequestType() == true;
    }

    /**
     * Check if submitted request is valid.
     *
     * @return bool True if request is valid, False if not
     */
    public function isValidRequest()
    {
        return ! empty($this->getMSISDN()) && ! empty($this->getSessionId());
    }

    /**
     * Check if request is a Timeout request.
     *
     * @return bool Request received is a timeout request or not
     */
    public function isTimeoutRequest()
    {
        return false;
    }

    /** TEMPORAL METHOD. TO BE REMOVED IN FAVOUR OF PROVIDER PROCESSOR KNOWING THEIR RESPONSE PROCESSOR CLASS */
    public function response($page)
    {
        return (new Response())->format($page);
    }

    /**
     * Generate a unique string to be used as the session id for this request.
     *
     * @return string Random string
     */
    protected function generateUniqueStringForSessionId()
    {
        return ceil(rand(1000, 9999) * 10000);
    }
}
