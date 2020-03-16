<?php

namespace Dbilovd\PHUSSD\Pages;

use Dbilovd\PHUSSD\Events\IssueReported;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ShowOwnerBalance extends BasePage
{
	/**
	 * Type of USSD Response given by this page.
	 * 
	 * @var string
	 */
	public $responseType = "end";

	/**
	 * Search for an owner and return matches for selection
	 *
	 * @param String $searchTerm Phone number of owner to search with
	 * @return Array Matching owner(s)
	 */
	public function fetchOwnerBalance($ownerId)
	{
		$client = new Client();
		$apiBaseUrl = config('rms.apiBaseUrl');
		$bearerToken = config('rms.bearerToken');
		$accountBalanceResponse = $client->get("{$apiBaseUrl}/owners/{$ownerId}/account-balance", [
			"headers"	=> [
				'Authorization'	=> "Bearer {$bearerToken}"
			]
		]);
		if ($accountBalanceResponse->getStatusCode() == "200") {
			$accountBalanceResult = json_decode(
				(String) $accountBalanceResponse->getBody()
			);
			Log::debug("Owner account balance", compact('accountBalanceResult'));
			$accountBalanace = $accountBalanceResult->data->accountBalance;
			return $accountBalanace;
		}

		return "N/A";
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

		$ownerBalance = $this->fetchOwnerBalance($ownerSelected['ownerId']);

		return "Account Balance:\r\n\r\nGHS " . number_format($ownerBalance, 2);
	}
}