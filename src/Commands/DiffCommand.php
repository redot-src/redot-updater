<?php

namespace Redot\Updater\Commands;

use function Laravel\Prompts\info;

class DiffCommand extends BaseCommand
{
    /**
     * The console command name.
     */
    protected $name = 'redot:diff';

    /**
     * The console command description.
     */
    protected $description = 'Get the diff between the local codebase and the latest redot dashboard version';

    /**
     * Handle the command
     */
    public function handle()
    {
        info('Open the following URL to see the diff:');
        info('https://redot.dev/projects/' . $this->project . '/diff');
    }
}
