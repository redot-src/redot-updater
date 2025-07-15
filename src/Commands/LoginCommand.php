<?php

namespace Redot\Updater\Commands;

use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class LoginCommand extends BaseCommand
{
    /**
     * The console command name.
     */
    protected $name = 'redot:login';

    /**
     * The console command description.
     */
    protected $description = 'Login to redot.dev to grab the API key';

    /**
     * Handle the command
     */
    public function handle()
    {
        if ($this->hasCredentials()) {
            error('You are already logged in');

            return;
        }

        $email = text('Enter your email address', required: true, placeholder: 'john@doe.com', validate: fn ($value) => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email address');
        $password = password('Enter your password', required: true, placeholder: '********', validate: fn ($value) => strlen($value) >= 8 ? null : 'Password must be at least 8 characters long');

        $response = Http::post('https://redot.dev/api/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->failed()) {
            error($response->json('message'));

            return;
        }

        $token = $response->json('payload.token');

        info('Logged in successfully, fetching projects...');

        $response = Http::withToken($token)->get('https://redot.dev/api/projects');

        if ($response->failed()) {
            error($response->json('message'));

            return;
        }

        $projects = collect($response->json('payload'))->filter(fn ($project) => $project['is_active']);

        if ($projects->isEmpty()) {
            error('No active projects found');

            return;
        }

        $projects = $projects->mapWithKeys(fn ($project) => [$project['slug'] => $project['name']])->toArray();
        $project = select('Select a project', $projects, required: true);

        $this->token = $token;
        $this->project = $project;

        $this->saveCredentials();

        info('Logged in successfully');
    }
}
