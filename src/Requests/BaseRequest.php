<?php

namespace Dbilovd\PHUSSD\Requests;

use Dbilovd\PHUSSD\Contracts\Requests;
use Illuminate\Http\Request;

class BaseRequest implements Requests
{
	/**
	 * Response header content type
	 * 
	 * @var string
	 */
	public $responseContentType = 'text/plain';

	/**
	 * Field name for Session ID field of current session
	 * 
	 * @var String
	 */
	protected $sessionIdFieldName = "SESSION_ID";

	/**
	 * Field name for User submitted USSD string
	 * 
	 * @var String
	 */
	protected $ussdStringFieldName = "USSD_STRING";

	/**
	 * Service Code
	 * 
	 * @var String
	 */
	protected $serviceCodeFieldName = "serviceCode";

	/**
	 * Phone number making request
	 * 
	 * @var String
	 */
	protected $msisdnFieldName = "MSISDN";

	/**
	 * Network making request
	 * 
	 * @var String
	 */
	protected $networkFieldName = false;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->setRequestFromHTTP();
	}

	/**
	 * Set Request from HTTP request received
	 *
	 * @return void
	 */
	public function setRequestFromHTTP()
	{
		if (function_exists('request')) {
			$this->request = request();
		} else {
			$this->request = json_decode(json_encode([]));
		}
	}

	/**
	 * Fetch session ID for current request
	 *
	 * @return String Session ID
	 */
	public function getSessionId()
	{
		return $this->request->get($this->sessionIdFieldName);
	}

	/**
	 * Fetch USSD string for current request
	 *
	 * @return String USSD String
	 */
	public function getUSSDString()
	{
		return $this->request->get($this->ussdStringFieldName);
	}

	/**
	 * Fetch service code for current request
	 *
	 * @return String Service Code
	 */
	public function getServiceCode()
	{
		return $this->request->get($this->serviceCodeFieldName);
	}

	/**
	 * Fetch MSISDN for current request
	 *
	 * @return String MSISDN
	 */
	public function getMSISDN()
	{
		return $this->request->get($this->msisdnFieldName);
	}

	/**
	 * Fetch Network used for current request
	 *
	 * @return String Network handle
	 */
	public function getNetwork()
	{
		return $this->networkFieldName ?
			$this->request->get($this->networkFieldName) : false;
	}

	/**
	 * Check if current request is the initial request
	 *
	 * @return Boolean True if this request is the first request in session
	 */
	public function isInitialRequest ()
	{
		return $this->getUSSDString() == '';
	}

	/**
	 * Check if current request is a request to cancel session
	 *
	 * @return Boolean True if this request is a cancellation request
	 */
	public function isCancellationRequest ()
	{
		return false;
	}

	/**
	 * Check if request is a Timeout request
	 *
	 * @return Boolean Request received is a timeout request or not
	 */
	public function isTimeoutRequest ()
	{
		return false;
	}

	/**
	 * Return matching response
	 *
	 * @return mixed Reponse
	 */
	public function response($page)
	{
		return "{$page->responseType()} {$page->message()}";
	}

	/**
	 * Check if submitted request is valid
	 *
	 * @return Boolean True if request is valid, False if not
	 */
	public function isValidRequest()
	{
		return (! empty($this->getMSISDN()) && ! empty($this->getServiceCode()));
	}

	public function getResponseType ($type)
	{
		switch ($type) {
			case 'end':
				return "END";
				break;

			case 'continue':
			default:
				return "CON";
				break;
		}
	}
}