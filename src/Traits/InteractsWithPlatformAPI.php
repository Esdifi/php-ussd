<?php

namespace Dbilovd\PHUSSD\Traits;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait InteractsWithPlatformAPI
{

    /**
     * Get API token from API
     *
     * @return String Access token returned from server
     */
    protected function apiGetAccessToken()
    {
        // Request for authorisation code
        $http = new Client();
        $baseUrl = config("gethelp.baseUrl");
        $tokenRequestUrl = "{$baseUrl}/oauth/token";
        $tokenRequestData = [
            'grant_type'    => 'client_credentials',
            'client_id'     => config("gethelp.apiClientId"),
            'client_secret' => config("gethelp.apiClientSecret"),
            'scope'         => '*',
        ];
        Log::debug("token request data", compact('tokenRequestData'));
        try {
            $tokenResponse = $http->post($tokenRequestUrl, [
                'form_params' => $tokenRequestData
            ]);
        } catch(Exception $e) {
            Log::error("an Error Occurred while sending request to store a new issue: {$e->getMessage()}");
            return false;
        }
        return json_decode((string) $tokenResponse->getBody(), true)['access_token'];
    }

}