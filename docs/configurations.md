# Configurations

In here, we will all the options for configuring your USSD application

### Initial screen to load.
The first thing to do when you want to setup a different screen to load as the initial screen, is to create the screen.
You can create a Screen anywhere in your application. We will be using the folder `app/USSD/Screens` to store all our Screen
classes.

For our initial screen, let us call it InitialScreen. I will create it in the folder `app/USSD/Screens/InitialScreen.php` this
class should extend the `\Esdifi\PHP_USSD\Screens\Screen` class.

```
<?php

namespace App\USSD\Screens;

use Esdifi\PHP_USSD\Screens\Screen;

class InitialScreen extends Screen
{

    /**
     * Default response type
     *
     * @var string
     */
    public $responseType = 'end';
    
    /**
     * Message to display to user
     * 
     * @var string
     */
    public $message = "Welcome to our USSD Application.";
}

``` 

Next we can configure the application to use our new class as our initial screen.

For that we need to update our `config/php-ussd.php` file.

We will update the `initialScreenClass` key with the class name of our initial screen class

```
<?php

use App\USSD\Screens\InitialScreen;

return [
    // ...
    
    "initialScreenClass"   => InitialScreen::class,
    
    // ...
];

```

After this, make sure you update your cached config values. `php artisan config:cache`

You can place a request to the initial screen of your application and you should see the text: 
"Welcome to our USSD Application."

