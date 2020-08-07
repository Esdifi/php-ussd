<?php

namespace Dbilovd\PHP_USSD\GatewayProviders\General;

use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderContract;
use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;
use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderResponseContract;

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
