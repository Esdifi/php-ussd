<?php

namespace Esdifi\PHP_USSD\GatewayProviders\Nalo;

use Esdifi\PHP_USSD\GatewayProviders\General\Provider as GeneralProvider;

class Provider extends GeneralProvider
{
    public function __construct($httpRequest, $sessionManager)
    {
        $this->request = new Request($httpRequest, $sessionManager);
        $this->response = new Response($this->request);
    }
}
