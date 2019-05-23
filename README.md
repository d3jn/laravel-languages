> ### This package will no longer be maintained. It is very simple and does what it was created for, so I have no plans on supporting it in the future.

# Laravel Languages

Very basic package that takes care of handling routing for multilanguage sites. Only basic features with minimum of configuration.

## Getting Started

### Prerequisites

This package was developed using Laravel 5.6.

### Installing

Use composer to install this package:

```
composer require d3jn/laravel-languages
```

`Laravel Package Auto-Discovery` should handle adding service provider and alias for you automatically or you can manually add those to your providers and aliases list in ```app.php```:

```php
'providers' => [
    ...

    D3jn\LaravelLanguages\LanguagesServiceProvider::class,

    ...
],

...

'aliases' => [
    ...

    'Languages' => D3jn\LaravelLanguages\Facades\Languages::class,

    ...
],
```

Lastly, you should publish it's configuration file:

```
php artisan vendor:publish --provider="D3jn\LaravelLanguages\LanguagesServiceProvider"
```

Now you can proceed with configuring this package for your needs.

### Configuration

Open ```config/languages.php```. All available configurations are well documented there.

## Usage

Specify callable for properly setting locale in your routes file, for example:

```php
Languages::setLocaleCallable(function ($locale) {
    // Example of initializing various packages with locale:
    Carbon::setLocale($locale);
    LaravelGettext::setLocale($locale);
    App::setLocale(LaravelGettext::getLocaleLanguage());
});
```

Then define route group for your application routes and use ```Languages::init()``` as it's prefix and ```languages``` middleware:

```php
Route::group(
    ['prefix' => Languages::init(), 'middleware' => ['languages']],
    function () {
        Route::get('/', function () {
            return __('hello');
        });

        ...
    }
);
```

## Built With

* [Laravel](http://laravel.com) - The web framework used

## Authors

* **Serhii Yaniuk** - [d3jn](https://twitter.com/d3jn_)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
