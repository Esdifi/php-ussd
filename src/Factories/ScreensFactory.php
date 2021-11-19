<?php

namespace Esdifi\PHP_USSD\Factories;

use Esdifi\PHP_USSD\Contracts\ScreenContract;
use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderRequestContract;
use Esdifi\PHP_USSD\Managers\Configurations\ConfigurationManagerContract;
use Esdifi\PHP_USSD\Traits\InteractsWithSession;
use Esdifi\PHP_USSD\Traits\ThrowsExceptions;

class ScreensFactory
{
    use InteractsWithSession, ThrowsExceptions;

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
     * Class name of screen.
     *
     * @var string
     */
    protected $initialScreenClass;

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
     * Make and return a USSD screen.
     *
     * @param $type
     * @param ScreenContract|null $previousScreen
     * @param null $userResponse
     * @return ScreenContract A class that implements the screens contract
     */
    public function make($type, ScreenContract $previousScreen = null, $userResponse = null): ScreenContract
    {
        switch ($type) {
            case 'subsequent':
                return $this->subsequentScreens($previousScreen, $userResponse);
                break;

            case 'initial':
            default:
                return $this->initialScreen();
                break;
        }
    }

    /**
     * Instantiate and return the initial screen.
     *
     * @return ScreenContract
     */
    protected function initialScreen(): ScreenContract
    {
        $initialScreenClassName = $this->getInitialScreenClass();

        return new $initialScreenClassName($this->request);
    }

    /**
     * Handle requests that are not the initial, cancellation or timeout requests.
     *
     * @param ScreenContract $previousScreen
     * @param $userResponse
     * @return ScreenContract Response string
     */
    protected function subsequentScreens(ScreenContract $previousScreen, $userResponse): ScreenContract
    {
        $nextScreenClassName = $previousScreen->next($userResponse);

        if (! $nextScreenClassName) {
            $this->throwInvalidUserResponseException();
        }

        return new $nextScreenClassName($this->request, $userResponse);
    }

    /**
     * @return string
     */
    protected function getInitialScreenClass(): string
    {
        $initialScreen = $this->config->get('php-ussd.initialScreenClass');

        if (! $initialScreen) {
            $initialScreen = \Esdifi\PHP_USSD\Screens\Home::class;
        }

        return $initialScreen;
    }
}
