<?php

namespace Dbilovd\PHUSSD\Factories;


use Dbilovd\PHUSSD\GatewayProviders\GatewayProviderContract;
use Dbilovd\PHUSSD\Managers\Configurations\ConfigurationManagerContract;

class GatewayRequestProcessorFactory
{
	/**
	 * Available Processors
	 * 
	 * @var array
	 */
	protected $processors = [
        'general'	=> \Dbilovd\PHUSSD\GatewayProviders\General\Provider::class,
        'hubtel'	=> \Dbilovd\PHUSSD\GatewayProviders\Hubtel\Provider::class,
	];

	/**
	 * Configuration manager
	 *
	 * @var ConfigurationManagerContract
	 */
	protected $config;

    /**
     * Constructor
     *
     * @param ConfigurationManagerContract $config
     */
	public function __construct(ConfigurationManagerContract $config)
	{
		$this->config = $config;
	}

	/**
	 * Make and return a Gateway Request Processort
	 *
	 * @return GatewayProviderContract
	 */
	public function make($httpRequest)
	{
		$gatewayProviderKey = $this->config->get("phussd.defaultServiceProvider");

		if (!$gatewayProviderKey || !array_key_exists($gatewayProviderKey, $this->processors)) {
			$gatewayProviderKey = 'general';
		}

		return (new $this->processors[$gatewayProviderKey]($httpRequest));
	}

}