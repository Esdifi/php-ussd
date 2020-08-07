# Your First USSD App

## Installation
Make sure you have completed the installation of the package.

## Setup

### Controller
The first thing to do is to setup the Controller that will handle all requests to our USSD application.
The new controller will extend `\Dbilovd\PHUSSD\Http\Controllers\UssdController`.

NB: You can place the controller anywhere you want to. For this guide we will be placing it in the default controllers' 
folder **_app/Http/Controllers_** of a Laravel application.

```
<?php
namespace App\Http\Controllers;

use Dbilovd\PHUSSD\Http\Controllers\UssdController;

class USSDApplicationController extends UssdController
{
  // ...
}
```

The controller class extended `Dbilovd\PHUSSD\Http\Controllers\BaseController` has a method called `home`. This method 
will handle all USSD requests.

### Routes
To be able to receive requests we need to open an endpoint that will handle our requests. For that we will setup a 
route that points to the `home` method of our new controller `USSDApplicationController`. We will be updating the
 `routes/web.php` file.

```
<?php
// ...

Route::get('/ussd-app', 'USSDApplicationController@home');

// ...
```

NB: you can use a `POST` method or both `POST` and `GET` methods using any of the following:
* `Route::post('/ussd-app', 'USSDApplicationController@home');`
* `Route::match([ 'GET', 'POST' ], '/ussd-app', 'USSDApplicationController@home');` 

## Results
That all, you are now ready to begin building your USSD application.
Start your Laravel server using `php artisan serve` and visit `http://127.0.0.1:8000/ussd-app` in a browser.

You should see the first screen 
```
CON Welcome to PHP USSD (PhUSSD).

1. Continue
2. Cancel
```

You are seeing this message because by default we are using a General plain-text based gateway provider.
A break down of the message:  
1. CON: This tells the user's phone that we are expecting a response and so an input field will be provided for the user
2. Message: "Welcome to PHP USSD (PhUSSD) package demo.\r\n1. Continue\r\n2. Cancel" This is the message that will be 
displayed on the user's phone. This contains the menus.

If you use the application on your phone you will be given an input field to enter your response. Right now since we are
using our browser's address bar as our simulator, we can pass our input to our USSD application using the GET parameter:
_ussdString_. 

Before we do that, since the interaction between the USSD Gateway Provider and our application is stateless, we will 
need to pass a sessionId with every request. For our Plain-Text Gateway Provider, we will use the field name _sessionId_  
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
2. Message: "Done. You entered: 1" This is the message that will be displayed on the user's phone. In this example it 
displayes the user's input.

