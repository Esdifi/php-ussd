# Configurations

In here, we will all the options for configuring your USSD application

### Initial page to load.
The first thing to do when you want to setup a different page to load as the initial page, is to create the page.
You can create a Page anywhere in your application. We will be using the folder `app/USSD/Pages` to store all our Page
classes.

For our initial page, let us call it InitialPage. I will create it in the folder `app/USSD/Pages/InitialPage.php` this
class should extend the `\Dbilovd\PHUSSD\Pages\BasePage`

```
<?php

namespace App\USSD\Pages;

use Dbilovd\PHUSSD\Pages\BasePage;

class InitialPage extends BasePage
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

Next we can configure the application to use our new class as our initial page.

For that we need to update our `config/phussd.php` file.

We will update the `initialPageClass` key with the class name of our initial page class

```
<?php

use App\USSD\Pages\InitialPage;

return [
    // ...
    
    initialPageClass"   => InitialPage::class,
    
    // ...
];

```

After this, make sure you update your cached config values. `php artisan config:cache`

You can place a request to the initial page of your application and you should see the text: 
"Welcome to our USSD Application."

