# PHP USSD

## Getting Started

### Laravel

##### Installation
You can install the package using the following composer command

`composer require dbilovd/phussd@dev-master`

##### Initial Setup
Create a Controller to handle all requests to our USSD API service.
The controller should extend `\Dbilovd\PHUSSD\Http\Controllers\BaseController;`.

NB: You can place the controller anywhere you want to. I will be placing mine
in the **_app/Http/Controllers_** folder of my Laravel application.

```
<?php
namespace App\Http\Controllers;

use Dbilovd\PHUSSD\Http\Controllers\BaseController;

class USSDApplicationController extends BaseController
{
  // ...
}
```

The class `Dbilovd\PHUSSD\Http\Controllers\BaseController` contains a `home` method.
This method handles all USSD requests. When you extend this class, you get this method in your controller automatically.

Next, we will setup a route that points to the home method of your new controller 
`routes/web.php`

```
<?php
// ...

Route::get('/ussd-app', 'USSDApplicationController@home');

// ...
```

NB: you can use a `POST` method or both `POST` and `GET` methods using 
`Route::match([ 'GET', 'POST' ], '/ussd-api', 'USSDApplicationController@home')`. 

##### Test our setup so far
That all, you are now ready to begin building your USSD application.
Start your Laravel server using `php artisan serve` and visit `http://127.0.0.1:8000/ussd-app` in a browser.

You should see the first screen 
```
CON Welcome to PHP USSD (PhUSSD) package demo.

1. Continue
2. Cancel
```

You are seeing this message because by default we are using a General plain-text based gateway provider.
A break down of the message:  
1. CON: This tells the user's phone that we are expecting a response and so an input field will be provided for the user
2. Message: "Welcome to PHP USSD (PhUSSD) package demo.\r\n1. Continue\r\n2. Cancel" This is the message that will be 
displayed on the user's phone. This contains the menus.

## Concepts

### Gateway Provider (or Provider)
Gateway Providers are the organisations/services that are sending the requests to your application.
Eg: Hubtel (Ghana), etc.
PHUSSD comes with 2 Gateway Providers (General, Hubtel) by default. This however can be easily be expanded to add more.
I will walk you through creatinga Gateway Provider shortly. 

Each Provider sends a request (GatewayProviderRequestContract) and expects a response (GatewayProviderResponseContract).

GatewayProviderRequestContract  
This handles the request. It validates the request and retrieves data from the request. This data is used in the
GatewayProviderProcessor to process the USSD request. After successful processing, a GatewayProviderResponse 
is returned.

GatewayProviderResponseContract
This class is responsible for formatting the response from our USSD application to match a format the USSD provider 
understands.

