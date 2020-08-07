<?php

namespace Dbilovd\PHP_USSD\Http\Controllers;

use App\Http\Controllers\Controller;
use Dbilovd\PHP_USSD\Factories\GatewayRequestProcessorFactory;
use Dbilovd\PHP_USSD\Factories\HttpRequestFactory;
use Dbilovd\PHP_USSD\Factories\PagesFactory;
use Dbilovd\PHP_USSD\Factories\SessionManagerFactory;
use Dbilovd\PHP_USSD\Managers\Configurations\Laravel as LaravelConfiguration;
use Dbilovd\PHP_USSD\Services\CoreControllerService;
use Illuminate\Http\Response;

class UssdController extends Controller
{
	/**
	 * Home endpoint for all USSD requests
	 *
	 * @return Response
	 */
	public function home()
	{
		$config = new LaravelConfiguration();

        $httpRequest = (new HttpRequestFactory($config))->make();

        $sessionManager = (new SessionManagerFactory())->make();

		$gatewayProvider = (new GatewayRequestProcessorFactory($config))
			->make($httpRequest);

		$pagesFactory = (new PagesFactory($gatewayProvider->getRequest(), $config));

		return (new CoreControllerService($gatewayProvider, $sessionManager, $pagesFactory))
            ->handle();
	}
}
