# Main Menu
Laravel - Inertia - Vue3

## Support us

## Installation

You can install the package via composer:

```bash
composer require acitjazz/main-menu
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="main-menu-migrations"
php artisan migrate
```
or

```bash
php artisan migrate --path=vendor/acitjazz/main-menu/database/migrations
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="main-menu-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="main-menu-views"
```

## Usage

```php
$mainMenu = new AcitJazz\MainMenu();
echo $mainMenu->echoPhrase('Hello, AcitJazz!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Acit Jazz](https://github.com/AcitJazz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
