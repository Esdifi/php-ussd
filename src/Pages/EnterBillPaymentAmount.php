<?php

namespace Dbilovd\PHUSSD\Pages;

use Dbilovd\PHUSSD\Events\IssueReported;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class EnterBillPaymentAmount extends BasePage
{
	/**
	 * Type of USSD Response given by this page.
	 * 
	 * @var string
	 */
	public $responseType = "continue";

	/**
	 * Session Data Field Key
	 * 
	 * @var string
	 */
	protected $dataFieldKey = "billAmount";

	/**
	 * Next page class name
	 * 
	 * @var String
	 */
	public $nextPage = EnterGCRReceiptNumber::class;

	/**
	 * Search for an owner and return matches for selection
	 *
	 * @param String $searchTerm Phone number of owner to search with
	 * @return Array Matching owner(s)
	 */
	public function fetchBillsForAnOwner($ownerId)
	{
		$client = new Client();
		$apiBaseUrl = config('rms.apiBaseUrl');
		$bearerToken = config('rms.bearerToken');
		$searchResultsResponse = $client->get("{$apiBaseUrl}/owners/{$ownerId}/bills", [
			"headers"	=> [
				'Authorization'	=> "Bearer {$bearerToken}"
			]
		]);
		if ($searchResultsResponse->getStatusCode() == "200") {
			$searchResult = json_decode(
				(String) $searchResultsResponse->getBody()
			);
			Log::debug("Owner bills", compact('searchResult'));
			$bills = collect($searchResult->data);
			$bills = $bills->mapWithKeys(function ($bill, $key) {
				return [ ++$key => $bill ];
			});
			return $bills->toArray();
		}
		return [];
	}

	/**
	 * Return message to send back to client
	 *
	 * @return String Message to return to client
	 */
	public function message()
	{
		$searchTerm = json_decode(
			Redis::hGet("ussd_session_{$this->request->getSessionId()}", "data"), true
		);
		$selectedBill = $searchTerm['selectedBill'];
		Log::debug('SB', compact('selectedBill'));

		return "Enter amount: \r\n\r\n";
	}
}