<?php

namespace AcitJazz\MainMenu\Commands;

use Illuminate\Console\Command;

class MainMenuCommand extends Command
{
    public $signature = 'main-menu';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
