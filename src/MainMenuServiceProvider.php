<?php

namespace AcitJazz\MainMenu;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use AcitJazz\MainMenu\Commands\MainMenuCommand;

class MainMenuServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('main-menu')
            ->hasRoutes('web')
            ->hasViews()
            ->hasMigrations([
                    'create_main_menu_table',
                ])
            ->hasCommand(MainMenuCommand::class);
    }

    public function packageBooted()
    {
        // Publish JS/Vue resources to the main app
        $this->publishes([
            __DIR__ . '/../resources/js/admin/menu/' => resource_path('js/admin/menu'),
        ], 'menu-assets');
    }
}
