<?php

namespace Dbilovd\PHUSSD\Factories;

use Dbilovd\PHUSSD\Contracts\ConfigurationManagerInterface;

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
	 * @var \Dbilovd\PHUSSD\Contracts\ConfigurationManagerInterface
	 */
	protected $config;

    /**
     * Constructor
     *
     * @param ConfigurationManagerInterface $config
     */
	public function __construct(ConfigurationManagerInterface $config)
	{
		$this->config = $config;
	}

	/**
	 * Make and return a Gateway Request Processort
	 *
	 * @return GatewayRequestProcessor
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