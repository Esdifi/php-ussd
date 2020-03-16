<?php

namespace Dbilovd\PHUSSD\Traits;

use Dbilovd\PHUSSD\Traits\InteractsWithPlatformAPI;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

trait CanSubmitABillPayment
{
    use InteractsWithPlatformAPI;

    /**
     * Fetch available session data
     *
     * @return Array Session Data
     */
    public function fetchSessionData ($sessionId)
    {
        return Redis::hGetAll($sessionId);
    }

    /**
     * Format Session data for saving in database
     *
     * @param Array $sessionData 
     * @return Array Formatted session data for storing in DB
     */
    public function formatData($sessionData)
    {
        $sessionData = json_decode($sessionData['data'], true);
        Log::debug("SD", compact('sessionData'));

        $toReturn = [
            "billId"        => $sessionData['selectedBill']['billId'],
            "amount"        => $sessionData['billAmount'],
            "receiptSerial" => $sessionData['gcrReceiptNumber'],
        ];
        Log::debug("TR", compact('toReturn'));

        return $toReturn;
    }

    /**
     * Save issue permanently to DB
     *
     * @param Array $data Issue data to save with related items
     * @return Boolean True if the issue was saved successfully and false if not
     */
    public function submitBillPayment($data)
    {
        // $accessToken = $this->apiGetAccessToken();
        // if (! $accessToken) {
        //     Log::error("Could not fetch API access token from server.");
        //     return false;
        // }

        $dataToSave = $this->formatData($data);
        Log::debug('Data to Save Request', compact('dataToSave'));


        // Store issue on server
        $client = new Client();
        try {
            $apiBaseUrl = config('rms.apiBaseUrl');
            $bearerToken = config('rms.bearerToken');
            $url = "{$apiBaseUrl}/payments";
            $createPaymentRequest = $client->post($url, [ 
                'headers'       => [
                    'Accept'        => 'application/json',
                    'Authorization' => "Bearer {$bearerToken}",
                ],
                'form_params'   => $dataToSave 
            ]);
        } catch (\Exception $e) {
            Log::error("an Error Occurred while sending request to store a new issue: {$e->getMessage()}");
            return false;
        }

        if ($createPaymentRequest->getStatusCode() != "200") {
            return false;
        }

        $createdPaymentResponse = json_decode(
            (String) $createPaymentRequest->getBody()
        );

        if (! $createdPaymentResponse || ! property_exists($createdPaymentResponse, 'issue')) {
            return false;
        }

        return $createdPaymentResponse->issue;
    }
}