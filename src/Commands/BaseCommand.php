<?php

namespace Redot\Updater\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Testing\File;

abstract class BaseCommand extends Command
{
    /**
     * The token for the Redot API.
     */
    protected string $token = '';

    /**
     * The current project identifier.
     */
    protected string $project = '';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadCredentials();
    }

    /**
     * Load the credentials from the Redot file.
     */
    protected function loadCredentials()
    {
        $file = base_path('.redot');

        if (! File::exists($file)) {
            return;
        }

        $credentials = json_decode(File::get($file), true);

        $this->token = $credentials['token'];
        $this->project = $credentials['project'];
    }

    /**
     * Save the credentials to the Redot file.
     */
    protected function saveCredentials()
    {
        $credentials = [
            'token' => $this->token,
            'project' => $this->project,
        ];

        File::put(base_path('.redot'), json_encode($credentials, JSON_PRETTY_PRINT));

        info('Credentials saved to .redot file');
    }

    /**
     * Check if the credentials are valid.
     */
    protected function hasCredentials(): bool
    {
        return ! empty($this->token) && ! empty($this->project);
    }
}
