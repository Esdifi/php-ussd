<?php

namespace Esdifi\PHP_USSD\Managers\HttpRequests;

class Laravel implements HttpRequestManagerContract
{
    /**
     * [methodName description].
     *
     * @return [type] [description]
     */
    public function request()
    {
        return request();
    }
}
