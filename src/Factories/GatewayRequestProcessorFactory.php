<?php

namespace Esdifi\PHP_USSD\Factories;

use Esdifi\PHP_USSD\Contracts\SessionManagersInterface;
use Esdifi\PHP_USSD\GatewayProviders\GatewayProviderContract;
use Esdifi\PHP_USSD\Managers\Configurations\ConfigurationManagerContract;

class GatewayRequestProcessorFactory
{
    /**
     * Available Processors.
     *
     * @var array
     */
    protected $processors = [
        'general'           => \Esdifi\PHP_USSD\GatewayProviders\General\Provider::class,
        'hubtel'            => \Esdifi\PHP_USSD\GatewayProviders\Hubtel\Provider::class,
        'nalo'              => \Esdifi\PHP_USSD\GatewayProviders\Nalo\Provider::class,
        'africastalking'    => \Esdifi\PHP_USSD\GatewayProviders\AfricasTalking\Provider::class,
    ];

    /**
     * Configuration manager.
     *
     * @var ConfigurationManagerContract
     */
    protected $config;

    /**
     * Session manager.
     *
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * Constructor.
     *
     * @param ConfigurationManagerContract $config
     */
    public function __construct(
        ConfigurationManagerContract $config,
        SessionManagersInterface $sessionManager = null
    ) {
        $this->config = $config;
        $this->sessionManager = $sessionManager;
    }

    /**
     * Make and return a Gateway Request Processort.
     *
     * @return GatewayProviderContract
     */
    public function make($httpRequest)
    {
        $gatewayProviderKey = $this->config->get('php-ussd.defaultServiceProvider');

        if (! $gatewayProviderKey || ! array_key_exists($gatewayProviderKey, $this->processors)) {
            $gatewayProviderKey = 'general';
        }

        return new $this->processors[$gatewayProviderKey]($httpRequest, $this->sessionManager);
    }
}
