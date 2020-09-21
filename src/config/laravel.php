<?php

use Dbilovd\PHP_USSD\Screens\Home;

return [
    /**
     * The default USSD Gateway Provider to our application.
     */
    'defaultServiceProvider'	=> env('DEFAULT_USSD_PROVIDER', 'general'),

    /**
     * Set the default method of saving and managing cache data
     * Options: laravel-cache (default), redis.
     */
    'defaultSessionManager'		=> env('DEFAULT_USSD_SESSION_MANAGER', 'laravel-cache'),

    /**
     * The entry screen for the application.
     */
    'initialScreenClass'       	=> env('PHP_USSD_INITIAL_SCREEN', Home::class),

];
