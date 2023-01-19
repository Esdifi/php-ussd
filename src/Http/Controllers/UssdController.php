<?php

namespace Esdifi\PHP_USSD\Http\Controllers;

use Esdifi\PHP_USSD\Factories\GatewayRequestProcessorFactory;
use Esdifi\PHP_USSD\Factories\HttpRequestFactory;
use Esdifi\PHP_USSD\Factories\ScreensFactory;
use Esdifi\PHP_USSD\Factories\SessionManagerFactory;
use Esdifi\PHP_USSD\Managers\Configurations\Laravel as LaravelConfiguration;
use Esdifi\PHP_USSD\Services\CoreControllerService;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UssdController extends Controller
{
    /**
     * Home endpoint for all USSD requests.
     *
     * @return Response
     */
    public function home()
    {
        $config = new LaravelConfiguration();

        $httpRequest = (new HttpRequestFactory($config))->make();

        $sessionManager = (new SessionManagerFactory($config))->make();

        $gatewayProvider = (new GatewayRequestProcessorFactory($config, $sessionManager))
            ->make($httpRequest);

        $pagesFactory = (new ScreensFactory($gatewayProvider->getRequest(), $config));

        return (new CoreControllerService($gatewayProvider, $sessionManager, $pagesFactory))
            ->handle();
    }
}
