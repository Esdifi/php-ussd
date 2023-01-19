<?php

namespace Esdifi\PHP_USSD\GatewayProviders\AfricasTalking;

use Esdifi\PHP_USSD\GatewayProviders\General\Provider as GeneralProvider;

class Provider extends GeneralProvider
{
    public function __construct($httpRequest)
    {
        $this->request = new Request($httpRequest);
        $this->response = new Response($this->request);
    }
}
