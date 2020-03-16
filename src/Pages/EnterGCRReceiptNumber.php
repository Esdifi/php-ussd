<?php

namespace Dbilovd\PHUSSD\Pages;

use Dbilovd\PHUSSD\Events\BillPaymentFormCompleted;
use Dbilovd\PHUSSD\Events\IssueReported;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class EnterGCRReceiptNumber extends BasePage
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
	protected $dataFieldKey = "gcrReceiptNumber";

	/**
	 * Next page class name
	 * 
	 * @var String
	 */
	public $nextPage = BillPaymentSaved::class;

	/**
	 * Message string to return
	 * 
	 * @var String
	 */
	public $message = "Enter GCR Receipt Number: \r\n\r\n";

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
	 * Hook to fire events when the issue has been submitted
	 *
	 * @param String $sessionId The Store ID for session in question
	 * @return Void
	 */
	protected function fireEvents($sessionId)
	{
		event(new BillPaymentFormCompleted($sessionId));
	}
}