<?php

namespace Dbilovd\PHUSSD\Factories;

use Dbilovd\PHUSSD\Contracts\ConfigurationManagerInterface;
use Dbilovd\PHUSSD\Managers\Configurations\ConfigurationManagerContract;
use Dbilovd\PHUSSD\Managers\HttpRequests\Laravel;

class HttpRequestFactory
{
	/**
	 * Available Processors
	 * 
	 * @var array
	 */
	protected $processors = [
		// 'laravel'	=> request,
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
	 * @return mixed
	 */
	public function make()
	{
	    return (new Laravel())->request();

		// $defaultHttpRequestKey = 'laravel'; // $this->config->get("phussd.defaultServiceProvider");

		// if (! array_key_exists($defaultHttpRequestKey, $this->processors)) {
		// 	$defaultHttpRequestKey = 'laravel';
		// }

		// return new $this->processors[$defaultHttpRequestKey]();
	}
}