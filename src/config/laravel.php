<?php

use Dbilovd\PHUSSD\Pages\Home;

return [
	'defaultServiceProvider'	=> env('DEFAULT_USSD_PROVIDER', 'general'),
    'initialPageClass'          => env('PHUSSD_INITIAL_PAGE', Home::class)
];
