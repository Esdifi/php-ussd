<?php

use Esdifi\PHP_USSD\GatewayProviders\AfricasTalking\Provider as ATProvider;
use Esdifi\PHP_USSD\GatewayProviders\AfricasTalking\Request as ATRequest;
use Esdifi\PHP_USSD\GatewayProviders\AfricasTalking\Response as ATResponse;
use PHPUnit\Framework\TestCase;

class AfricasTalkingTest extends TestCase
{
    /** @test */
    public function getRequest_returns_an_instance_of_ats_request_class()
    {
        $http = [];
        $atPRovider = new ATProvider($http);
        $request = $atPRovider->getRequest();
        $this->assertTrue($request instanceof ATRequest);
    }

    /** @test */
    public function getResponse_returns_an_instance_of_nalos_response_class()
    {
        $http = [];
        $atPRovider = new ATProvider($http);
        $response = $atPRovider->getResponse();
        $this->assertTrue($response instanceof ATResponse);
    }
}
