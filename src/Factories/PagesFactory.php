<?php

namespace Dbilovd\PHP_USSD\Factories;

use Dbilovd\PHP_USSD\Contracts\PagesContract;
use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;
use Dbilovd\PHP_USSD\Managers\Configurations\ConfigurationManagerContract;
use Dbilovd\PHP_USSD\Traits\InteractsWithSession;

class PagesFactory
{
    use InteractsWithSession;

    /**
     * Request object.
     *
     * @var [type]
     */
    protected $request;

    /**
     * Config.
     *
     * @var
     */
    protected $config;

    /**
     * Class name of page.
     *
     * @var string
     */
    protected $initialPageClass;

    /**
     * Constructor.
     *
     * @param $request
     * @param ConfigurationManagerContract $config
     */
    public function __construct(GatewayProviderRequestContract $request, ConfigurationManagerContract $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * Make and return a USSD Page.
     *
     * @param $type
     * @param PagesContract|null $previousPage
     * @param null $userResponse
     * @return PagesContract A class that implements the Pages contract
     */
    public function make($type, PagesContract $previousPage = null, $userResponse = null): PagesContract
    {
        switch ($type) {
            case 'subsequent':
                return $this->subsequentPages($previousPage, $userResponse);
                break;

            case 'initial':
            default:
                return $this->initialPage();
                break;
        }
    }

    /**
     * Instantiate and return the initial page.
     *
     * @return PagesContract
     */
    protected function initialPage(): PagesContract
    {
        $initialPageClassName = $this->getInitialPageClass();

        return new $initialPageClassName($this->request);
    }

    /**
     * Handle requests that are not the initial, cancellation or timeout requests.
     *
     * @param PagesContract $previousPage
     * @param $userResponse
     * @return PagesContract Response string
     */
    protected function subsequentPages(PagesContract $previousPage, $userResponse): PagesContract
    {
        $nextPageClassName = $previousPage->next($userResponse);

        if (! $nextPageClassName) {
            $this->throwInvalidUserResponseException();
        }

        return new $nextPageClassName($this->request, $userResponse);
    }

    /**
     * @return string
     */
    protected function getInitialPageClass(): string
    {
        $initialPage = $this->config->get('php-ussd.initialPageClass');

        if (! $initialPage) {
            $initialPage = \Dbilovd\PHP_USSD\Pages\Home::class;
        }

        return $initialPage;
    }
}
