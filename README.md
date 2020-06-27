PHP USSD

How to use

# Laravel
1. Install package
2. Create a dedicated controller that extends `\Dbilovd\PHUSSD\Http\Controllers\BaseController;`
This controller will handle all USSD requests
`
<?php
namespace App\Http\Controllers;
use Dbilovd\PHUSSD\Http\Controllers\BaseController;
class USSDApplicationController extends BaseController
{
}


Now add a route that points to the home method of your new controller 
`routes/web.php`
`
<?php
...
Route::match([ 'GET', 'POST' ], '/ussd-api',  [
	'as'	=> 'home',
	'uses'	=> 'USSDApplicationController@home',
]);
...
`

## Test our setup so far
That all, you are now ready to begin building your USSD application.
Run `php artisan serve` and visit `` to see the first screen.
POST http://127.0.0.1:8000/ussd-api
{
	"MSISDN": "233547051251",
	"serviceCode": "*123#"
}

You should see the first screen 



## Making Changes to the First screen

