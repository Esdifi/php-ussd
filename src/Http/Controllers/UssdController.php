<?php

namespace Dbilovd\PHUSSD\Http\Controllers;

use App\Http\Controllers\Controller;
use Dbilovd\PHUSSD\Factories\GatewayRequestProcessorFactory;
use Dbilovd\PHUSSD\Factories\HttpRequestFactory;
use Dbilovd\PHUSSD\Factories\PagesFactory;
use Dbilovd\PHUSSD\Factories\SessionManagerFactory;
use Dbilovd\PHUSSD\Managers\Configurations\Laravel as LaravelConfiguration;
use Dbilovd\PHUSSD\Services\CoreControllerService;
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
