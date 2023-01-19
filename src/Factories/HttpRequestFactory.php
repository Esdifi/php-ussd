<?php

namespace Esdifi\PHP_USSD\Factories;

use Esdifi\PHP_USSD\Managers\Configurations\ConfigurationManagerContract;
use Esdifi\PHP_USSD\Managers\HttpRequests\Laravel;

class HttpRequestFactory
{
    /**
     * Available Processors.
     *
     * @var array
     */
    protected $processors = [
        // 'laravel'	=> request,
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
     * @return mixed
     */
    public function make()
    {
        return (new Laravel())->request();

        // $defaultHttpRequestKey = 'laravel'; // $this->config->get("php-ussd.defaultServiceProvider");

        // if (! array_key_exists($defaultHttpRequestKey, $this->processors)) {
        // 	$defaultHttpRequestKey = 'laravel';
        // }

        // return new $this->processors[$defaultHttpRequestKey]();
    }
}
