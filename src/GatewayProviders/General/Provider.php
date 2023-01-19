<?php

namespace Esdifi\PHP_USSD\GatewayProviders\General;

use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderContract;
use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;
use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderResponseContract;

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
