<?php

namespace Dbilovd\PHUSSD\Pages;

use Dbilovd\PHUSSD\Events\IssueReported;
use Dbilovd\PHUSSD\Pages\ShowOwnerBalance;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SearchForAnOwnerResults extends BasePage
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
	protected $dataFieldKey = "owner";

	/**
	 * Next page class name
	 * 
	 * @var String
	 */
	public $nextPage = SelectABillOfAnOwner::class;

	/**
	 * Search for an owner and return matches for selection
	 *
	 * @param String $searchTerm Phone number of owner to search with
	 * @return Array Matching owner(s)
	 */
	public function searchForOwner($searchTerm)
	{
		$client = new Client();
		$apiBaseUrl = config('rms.apiBaseUrl');
		$bearerToken = config('rms.bearerToken');
		$searchResultsResponse = $client->get("{$apiBaseUrl}/owners?phone={$searchTerm}", [
			"headers"	=> [
				'Authorization'	=> "Bearer {$bearerToken}"
			]
		]);
		if ($searchResultsResponse->getStatusCode() == "200") {
			$searchResult = json_decode(
				(String) $searchResultsResponse->getBody()
			);
			Log::debug("Search Result", compact('searchResult'));
			$owners = collect($searchResult->data);
			$owners = $owners->mapWithKeys(function ($owner, $key) {
				return [ ++$key => $owner ];
			});
			return $owners->toArray();
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
		$searchTerm = $searchTerm['ownerSearchTerm'];

		$menus = $this->searchForOwner($searchTerm);
		array_walk($menus, function(&$value, $key) {
			$value = "{$key}. " . implode(" ", [ $value->title, $value->firstName, $value->lastName ]);
		});
		$menus = implode("\r\n", $menus);
		return "Select an owner\r\n\r\n{$menus}";
	}

	/**
	 * Prepare user response for storing
	 *
	 * @return mixed Prepared user response for storing in DB
	 */
	public function preparedUserResponse($userResponse)
	{
		$searchTerm = json_decode(
			Redis::hGet("ussd_session_{$this->request->getSessionId()}", "data"), true
		);
		$searchTerm = $searchTerm['ownerSearchTerm'];

		$menus = $this->searchForOwner($searchTerm);

		return (array_key_exists($userResponse, $menus)) ?
			$menus[$userResponse] : $searchTerm;
	}

	/**
	 * Return an instance of the next child class depending on the user input
	 *
	 * @return \Dbilovd\PHUSSD\Contracts\Pages
	 */
	public function next($selectedOption)
	{
		// Fetch home option selected.
		$currentSessionData = json_decode(
			Redis::hGet("ussd_session_{$this->request->getSessionId()}", "data"), true
		);
		$homeOptionSelected = $currentSessionData['homeOptionSelected'];

		// If a payment is being collected, go to payments page
		// If a balance check is being done, go to balance page

		$className = false;
		switch ($homeOptionSelected) {
			case '1':
				$className = $this->nextPage;
				break;

			case '2':
				$className = ShowOwnerBalance::class;
				break;
		}
		
		return $className;
	}
}