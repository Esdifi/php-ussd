<?php

namespace Dbilovd\PHP_USSD\Http\Controllers;

use Dbilovd\PHP_USSD\Factories\GatewayRequestProcessorFactory;
use Dbilovd\PHP_USSD\Factories\HttpRequestFactory;
use Dbilovd\PHP_USSD\Factories\ScreensFactory;
use Dbilovd\PHP_USSD\Factories\SessionManagerFactory;
use Dbilovd\PHP_USSD\Managers\Configurations\Laravel as LaravelConfiguration;
use Dbilovd\PHP_USSD\Services\CoreControllerService;
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

        $gatewayProvider = (new GatewayRequestProcessorFactory($config))
            ->make($httpRequest);

        $pagesFactory = (new ScreensFactory($gatewayProvider->getRequest(), $config));

        return (new CoreControllerService($gatewayProvider, $sessionManager, $pagesFactory))
            ->handle();
    }
}
