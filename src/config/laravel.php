<?php

use Esdifi\PHP_USSD\Screens\Home;

return [
    /**
     * The default USSD Gateway Provider to our application.
     */
    'defaultServiceProvider'	=> env('PHP_USSD_DEFAULT_GATEWAY_PROVIDER', 'general'),

    /**
     * Set the default method of saving and managing cache data
     * Options: laravel-cache (default), redis.
     */
    'defaultSessionManager'		=> env('PHP_USSD_DEFAULT_SESSION_MANAGER', 'laravel-cache'),

    /**
     * The entry screen for the application.
     */
    'initialScreenClass'       	=> env('PHP_USSD_INITIAL_SCREEN', Home::class),

    /**
     * The default exception screen for the application.
     */
    'exceptionScreenClass'      => env('PHP_USSD_EXCEPTION_SCREEN', false),

];
