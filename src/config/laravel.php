<?php

use Dbilovd\PHP_USSD\Pages\Home;

return [
	'defaultServiceProvider'	=> env('DEFAULT_USSD_PROVIDER', 'general'),
    'initialPageClass'          => env('PHP_USSD_INITIAL_PAGE', Home::class)
];
