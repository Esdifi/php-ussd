# Installation

## Laravel Installation
### Requirements
Currently, the following system requirements must be met when using this library with Laravel.
* PHP >= 7.3
* Laravel >= 7.x

### Installation

#### Via Composer
You may install this package using Composer

```
composer require dbilovd/php-ussd
```

This will install the latest release. However, since this is a package still in the early stages of development, you can run `composer require dbilovd@php-ussd@dev-master` to have the latest updates.

The `Esdifi\PHP_USSD\LaravelServiceProvider` will be discovered and registers automatically.

### Configuration Files

After your installation is complete, you should publish the package's configurations files. You can do that using the following command:
```
php artisan vendor:publish --provider="Esdifi\PHP_USSD\LaravelServiceProvider"
``` 
This will create a new configuration file named `config/php-ussd.php`

See [Configurations](./configurations.md) for possible configuration options.



