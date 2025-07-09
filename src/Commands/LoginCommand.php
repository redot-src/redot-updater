<?php

namespace Redot\Updater\Commands;

use Illuminate\Console\Command;

class LoginCommand extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'redot:login';

    /**
     * The console command description.
     */
    protected $description = 'Login to Redot to grab the API key';

    /**
     * Handle the command
     */
    public function handle()
    {
        // ...
    }
}