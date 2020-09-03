<?php

namespace Dbilovd\PHP_USSD\GatewayProviders\Nalo;

use Dbilovd\PHP_USSD\GatewayProviders\General\Provider as GeneralProvider;

class Provider extends GeneralProvider
{
    public function __construct($httpRequest)
    {
        $this->request = new Request($httpRequest);
        $this->response = new Response($this->request);
    }
}
