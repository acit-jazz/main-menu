<?php

namespace AcitJazz\MainMenu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AcitJazz\MainMenu\MainMenu
 */
class MainMenu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AcitJazz\MainMenu\MainMenu::class;
    }
}
