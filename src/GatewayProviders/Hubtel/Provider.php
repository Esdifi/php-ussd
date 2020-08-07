<?php

namespace Dbilovd\PHUSSD\GatewayProviders\Hubtel;

use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderContract;
use \Dbilovd\PHUSSD\GatewayProviders\General\Provider as GeneralProvider;

class Provider extends GeneralProvider
{
    public function __construct($httpRequest)
    {
        $this->request = new Request($httpRequest);
        $this->response = new Response();
    }
}