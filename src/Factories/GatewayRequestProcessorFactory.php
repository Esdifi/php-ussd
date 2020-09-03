<?php

namespace Dbilovd\PHP_USSD\Factories;

use Dbilovd\PHP_USSD\GatewayProviders\GatewayProviderContract;
use Dbilovd\PHP_USSD\Managers\Configurations\ConfigurationManagerContract;

class GatewayRequestProcessorFactory
{
    /**
     * Available Processors.
     *
     * @var array
     */
    protected $processors = [
        'general'	=> \Dbilovd\PHP_USSD\GatewayProviders\General\Provider::class,
        'hubtel'	=> \Dbilovd\PHP_USSD\GatewayProviders\Hubtel\Provider::class,
        'nalo'      => \Dbilovd\PHP_USSD\GatewayProviders\Nalo\Provider::class,
    ];

    /**
     * Configuration manager.
     *
     * @var ConfigurationManagerContract
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param ConfigurationManagerContract $config
     */
    public function __construct(ConfigurationManagerContract $config)
    {
        $this->config = $config;
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

        return new $this->processors[$gatewayProviderKey]($httpRequest);
    }
}
