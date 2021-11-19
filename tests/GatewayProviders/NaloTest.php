<?php

use Esdifi\PHP_USSD\GatewayProviders\Nalo\Provider as NaloProvider;
use Esdifi\PHP_USSD\GatewayProviders\Nalo\Request as NaloRequest;
use Esdifi\PHP_USSD\GatewayProviders\Nalo\Response as NaloResponse;
use PHPUnit\Framework\TestCase;

class NaloTest extends TestCase
{
    /** @test */
    public function getRequest_returns_an_instance_of_nalos_request_class()
    {
        $http = [];
        $naloProvider = new NaloProvider($http);
        $request = $naloProvider->getRequest();
        $this->assertTrue($request instanceof NaloRequest);
    }

    /** @test */
    public function getResponse_returns_an_instance_of_nalos_response_class()
    {
        $http = [];
        $naloProvider = new NaloProvider($http);
        $response = $naloProvider->getResponse();
        $this->assertTrue($response instanceof NaloResponse);
    }
}
