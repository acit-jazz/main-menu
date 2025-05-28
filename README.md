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

Publish the views using

```bash
php artisan vendor:publish --tag="menu-assets"
```

## Usage
Open App\Http\Middleware\HandleInertiaRequests.php and Add
```php
    return [
        ...
        'menus' => MenuRepository::getByLocation('Header'),
        ...
    ];
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
