<?php


namespace Dbilovd\PHUSSD\GatewayProviders;


interface GatewayProviderRequestContract
{
	/**
	 * Fetch session ID for current request
	 *
	 * @return String Session ID
	 */
	public function getSessionId();

	/**
	 * Fetch USSD string for current request
	 *
	 * @return String USSD String
	 */
	public function getUSSDString();

	/**
	 * Fetch service code for current request
	 *
	 * @return String Service Code
	 */
	public function getServiceCode();

	/**
	 * Fetch MSISDN for current request
	 *
	 * @return String MSISDN
	 */
	public function getMSISDN();

	/**
	 * Check if request is a Timeout request
	 *
	 * @return Boolean Request received is a timeout request or not
	 */
	public function isTimeoutRequest ();

	/**
	 * Fetch and return User response from USSD string
	 *
	 * @return string User Response string
	 */
	public function getUserResponseFromUSSDString();

}