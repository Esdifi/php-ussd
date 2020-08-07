<?php

namespace Dbilovd\PHUSSD\GatewayProviders\General;

use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderContract;

class Provider implements GatewayProviderContract
{
    protected $request;
    protected $response;

    public function __construct($httpRequest)
    {
        $this->request = new Request($httpRequest);
        $this->response = new Response();
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }
}