<?php

namespace Dbilovd\PHUSSD\Requests;

class HubtelRequest extends BaseRequest
{
	/**
	 * Response header content type
	 * 
	 * @var string
	 */
	public $responseContentType = 'application/json';

	/**
	 * Field name for Session ID field of current session
	 * 
	 * @var String
	 */
	protected $sessionIdFieldName = "SessionId";

	/**
	 * Field name for User submitted USSD string
	 * 
	 * @var String
	 */
	protected $ussdStringFieldName = "Message";

	/**
	 * Service Code
	 * 
	 * @var String
	 */
	protected $serviceCodeFieldName = "ServiceCode";

	/**
	 * Phone number making request
	 * 
	 * @var String
	 */
	protected $msisdnFieldName = "Mobile";

	/**
	 * Phone number making request
	 * 
	 * @var String
	 */
	protected $networkFieldName = "Operator";

	/**
	 * Request Type field name
	 * 
	 * @var String
	 */
	protected $requestTypeFieldName = "Type";

	/**
	 * Get value of request
	 *
	 * @return String Request type
	 */
	public function getRequestType()
	{
		return $this->request->get($this->requestTypeFieldName);
	}

	/**
	 * Check if current request is the initial request
	 *
	 * @return Boolean True if this request is the first request in session
	 */
	public function isInitialRequest ()
	{
		return $this->getRequestType() == 'Initiation';
	}

	/**
	 * Check if current request is a request to cancel session
	 *
	 * @return Boolean True if this request is a cancellation request
	 */
	public function isCancellationRequest ()
	{
		return $this->getRequestType() == 'Release';
	}

	/**
	 * Check if request is a Timeout request
	 *
	 * @return Boolean Request received is a timeout request or not
	 */
	public function isTimeoutRequest ()
	{
		return $this->getRequestType() == 'Timeout';
	}

	/**
	 * Return matching response
	 *
	 * @return mixed Reponse
	 */
	public function response($page)
	{
		// This is intentionally brought here, so each page
		// still has one more chance to alter the responseType
		// when generating a message
		$message = $page->message();

		$response = [
			'Type' 		=> $page->responseType(),
			'Message' 	=> $message
		];
		return json_encode($response);
	}

	public function getResponseType ($type)
	{
		switch ($type) {
			case 'end':
				return "Release";
				break;

			case 'continue':
			default:
				return "Response";
				break;
		}
	}
}