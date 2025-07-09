<?php

namespace Redot\Updater\Commands;

use Illuminate\Console\Command;

class DiffCommand extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'redot:diff';

    /**
     * The console command description.
     */
    protected $description = 'Get the diff between the local codebase and the latest dashboard';

    /**
     * Handle the command
     */
    public function handle()
    {
        // ...
    }
}