<?php

namespace Dbilovd\PHP_USSD\Factories;

use Dbilovd\PHP_USSD\Contracts\SessionManagersInterface;
use Dbilovd\PHP_USSD\Managers\Configurations\ConfigurationManagerContract;
use Dbilovd\PHP_USSD\SessionManagers\LaravelCacheSessionManager;
use Dbilovd\PHP_USSD\SessionManagers\RedisSessionManager;

class SessionManagerFactory
{
    /**
     * List of available SessionManagersInterface implementations.
     *
     * @var array
     */
    protected $managers = [
        'laravel-cache' => LaravelCacheSessionManager::class,
        'redis'         => RedisSessionManager::class,
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
     * Make a new instace of a Session Manager.
     *
     * @return \Dbilovd\PHP_USSD\Contracts\SessionManagersInterface
     */
    public function make(): SessionManagersInterface
    {
        $sessionManagerKey = $this->config->get('php-ussd.defaultSessionManager');

        if (! $sessionManagerKey || ! array_key_exists($sessionManagerKey, $this->managers)) {
            $sessionManagerKey = 'laravel-cache';
        }

        return new $this->managers[$sessionManagerKey]();
    }
}
