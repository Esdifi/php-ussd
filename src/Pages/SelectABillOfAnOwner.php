<?php

namespace Dbilovd\PHUSSD\Pages;

use Dbilovd\PHUSSD\Events\IssueReported;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SelectABillOfAnOwner extends BasePage
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
	protected $dataFieldKey = "selectedBill";

	/**
	 * Next page class name
	 * 
	 * @var String
	 */
	public $nextPage = EnterBillPaymentAmount::class;

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
		$ownerSelected = $searchTerm['owner'];

		$menus = $this->fetchBillsForAnOwner($ownerSelected['ownerId']);
		array_walk($menus, function(&$value, $key) {
			$value = "{$key}. GHS {$value->amount} - " . $value->{$value->billable_type}->uniqueId;
		});
		$menus = implode("\r\n", $menus);
		return "Select a bill\r\n\r\n{$menus}";
	}

	/**
	 * Prepare user response for storing
	 *
	 * @todo  Think about reusing previous screens search resutl
	 *        instead of running another search again.
	 *        This will be to help reduce data inconsitencies because
	 *        search result data might have changes since last screen
	 * 
	 * @return mixed Prepared user response for storing in DB
	 */
	public function preparedUserResponse($userResponse)
	{
		$searchTerm = json_decode(
			Redis::hGet("ussd_session_{$this->request->getSessionId()}", "data"), true
		);
		$ownerSelected = $searchTerm['owner'];


		$menus = $this->fetchBillsForAnOwner($ownerSelected['ownerId']);

		return (array_key_exists($userResponse, $menus)) ?
			$menus[$userResponse] : $searchTerm;
	}
}