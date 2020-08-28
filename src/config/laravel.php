<?php

use Dbilovd\PHP_USSD\Screens\Home;

return [
    /**
     * The default USSD Gateway Provider to our application.
     */
    'defaultServiceProvider'	=> env('DEFAULT_USSD_PROVIDER', 'general'),

    /**
     * The entry screen for the application.
     */
    'initialScreenClass'       	=> env('PHP_USSD_INITIAL_SCREEN', Home::class),

];
