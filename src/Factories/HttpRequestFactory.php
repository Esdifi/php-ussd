<?php

namespace Dbilovd\PHUSSD\Factories;

use Dbilovd\PHUSSD\Contracts\ConfigurationManagerInterface;

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
	 * @var \Dbilovd\PHUSSD\Contracts\ConfigurationManagerInterface
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct(ConfigurationManagerInterface $config)
	{
		$this->config = $config;
	}

	/**
	 * Make and return a Gateway Request Processort
	 *
	 * @return \GatewayRequestProcessor 
	 */
	public function make()
	{
		return request();
		
		// $defaultHttpRequestKey = 'laravel'; // $this->config->get("phussd.defaultServiceProvider");

		// if (! array_key_exists($defaultHttpRequestKey, $this->processors)) {
		// 	$defaultHttpRequestKey = 'laravel';
		// }

		// return new $this->processors[$defaultHttpRequestKey]();
	}
}