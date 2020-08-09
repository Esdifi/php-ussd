# Your First USSD App
This document aims to guide you in building a minimal USSD application using this package.

## Installation
Before we proceed, ensure you have completed the [installation](./installation.md) of the package.

## Setup

### Controller
The first thing to do is to set up the Controller that will handle all requests to our USSD application.
This controller should extend `\Dbilovd\PHP_USSD\Http\Controllers\UssdController`.

NB: You can place the controller anywhere in your application. For this guide, we will be placing it in Laravel's default controllers' 
folder: **_app/Http/Controllers_**.

```
<?php
namespace App\Http\Controllers;

use Dbilovd\PHP_USSD\Http\Controllers\UssdController;

class USSDApplicationController extends UssdController
{
  // ...
}
```

The class `Dbilovd\PHP_USSD\Http\Controllers\BaseController` contains a method called `home`. We will be pointing our route to this method.

### Routes
After setting up our controller, we need to open an endpoint that will handle our requests. Add the following code to your `routes/web.php` file.

Note: You can use your `routes/api.php` file instead if you so wish.

```
<?php
// ...

Route::get('/ussd-app', 'USSDApplicationController@home');

// ...
```

You can use a `POST` method or match both `POST` and `GET` methods using any of the following:
: 
```php
<?php
// POST
Route::post('/ussd-app', 'USSDApplicationController@home');

// POST & GET
Route::match(
  [ 'GET', 'POST' ], 
  '/ussd-app', 
  'USSDApplicationController@home'
); 

```

**NB:** If you are using a `POST` method, you will need to exclude the route from CRSF protection. Add the route `ussd-app` to the `$except` property of the `VerifyCsrfToken` (`app/Http/Middleware/VerifyCsrfToken.php`) middleware. [Learn more](https://laravel.com/docs/7.x/csrf#csrf-excluding-uris)

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
    * The URIs that should be excluded from CSRF verification.
    *
    * @var array
    */
  protected $except = [
    'ussd-app'
  ];

}
```

Hurray, you have built your very first USSD application. Now, let's use it.

## Results
You will need a simulator to use your application, but for this guide, we will be using our browser as a pseudo-simulator.

Start your Laravel server using `php artisan serve` and visit `http://127.0.0.1:8000/ussd-app` in a browser.

You should see the following message.
```
CON Welcome to PHP USSD (PhUSSD).

1. Continue
2. Cancel
```

You are seeing this message because, by default, we are using a General plain-text based gateway provider.

What does this mean?

1. _CON_ tells the user's phone that we are expecting a response and so an input field will be provided to the user.
2. "Welcome to PHP USSD (PhUSSD) package demo.\r\n1. Continue\r\n2. Cancel" This is the message that will be displayed on the user's phone. 

If you use a simulator will be given an input field to enter your response. However, since we are using our browser as a simulator, we can pass our input to our USSD application using the _ussdString_ GET parameter.

Before we do that, since the interaction between the USSD Gateway Provider and our application is stateless, we will need to pass a sessionId with every request. For our Plain-Text Gateway Provider, we will use the field name _sessionId_  
NB: This is done automatically for you by the Gateway Provider.

So visit `http://127.0.0.1:8000/ussd-app?sessionId=RANDOM_STRING_TO_IDENTIFY_SESSION`, you will get an identical 
response as we got earlier.

Now to parse your user input, visit 
`http://127.0.0.1:8000/ussd-app?sessionId=RANDOM_STRING_TO_IDENTIFY_SESSION&ussdString=*123*1#` 

You should see another screen with the following text

```
END Done. You entered: 1
```
A break down of the message:  
1. END: This tells the user's phone that we are NOT expecting a response and so NO input field will be shown to the user
2. Message: "Done. You entered: 1" This is the message that will be displayed on the user's phone. In this example, it displays the user's input.
