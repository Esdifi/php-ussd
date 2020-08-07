<?php

namespace Dbilovd\PHUSSD\GatewayProviders\General;

use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderContract;
use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderRequestContract;
use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderResponseContract;

class Provider implements GatewayProviderContract
{
    protected $request;
    protected $response;

    public function __construct($httpRequest)
    {
        $this->request = new Request($httpRequest);
        $this->response = new Response();
    }

    public function getRequest(): GatewayProviderRequestContract
    {
        return $this->request;
    }

    public function getResponse(): GatewayProviderResponseContract
    {
        return $this->response;
    }
}