# Installation

## Laravel Installation
### Requirements
The following must be met when using this library with Laravel
* PHP >= 7.1
* Laravel >= 5.8

### Installation

#### Via Composer
You may install this package using Composer

```
composer require dbilovd/php-ussd@dev-master
```

The `Dbilovd\PHP_USSD\LaravelServiceProvider` will be discovered and registers automatically.

### Configuration

First, you want to publish the configurations files. You can do that using the following commands
```
php artisan vendor:publish --provider="Dbilovd\PHP_USSD\LaravelServiceProvider"
``` 
This will create a new configuration file named `config/php-ussd.php`