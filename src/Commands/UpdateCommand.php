<?php

namespace Redot\Updater\Commands;

use Illuminate\Support\Facades\File;
use ZipArchive;

use function Laravel\Prompts\error;
use function Laravel\Prompts\spin;

class UpdateCommand extends BaseCommand
{
    /**
     * The console command name.
     */
    protected $name = 'redot:update';

    /**
     * The console command description.
     */
    protected $description = 'Update this project codebase to the latest redot dashboard version';

    /**
     * Handle the command
     */
    public function handle()
    {
        $this->cleanUp();

        // Define paths
        $tmpPath = $this->basePath . '/tmp';
        $zipPath = $tmpPath . '/download.zip';
        $path = $tmpPath . '/extracted';

        // Ensure tmp directory exists
        File::ensureDirectoryExists($tmpPath);

        // Get download endpoint
        $endpoint = "$this->endpoint/projects/$this->project/download?commit=HEAD";
        $response = $this->createHttpClient()->get($endpoint);

        // If download failed, show error and exit
        if ($response->failed()) {
            error($response->json('message'));

            return;
        }

        // Get download URL
        $download = $response->json('payload.download');

        // Get diff endpoint
        $endpoint = "$this->endpoint/projects/$this->project/diff";
        $response = $this->createHttpClient()->get($endpoint);

        // If diff failed, show error and exit
        if ($response->failed()) {
            error($response->json('message'));

            return;
        }

        // Download dashboard
        spin(fn () => File::put($zipPath, $this->createHttpClient()->get($download)->body()), 'Downloading dashboard...');

        // Unarchive dashboard
        spin(fn () => $this->unarchive($zipPath, $path), 'Unarchiving dashboard...');

        // Apply diff
        spin(fn () => array_map(fn ($file) => $this->applyFileDiff($file, $path), $response->json('payload.files')), 'Applying diff...');

        // Clean up
        spin(fn () => $this->cleanUp(), 'Cleaning up...');

        // Show success message
        info('Dashboard updated successfully');
    }

    /**
     * Unarchive the zip file
     */
    protected function unarchive(string $path, string $destination)
    {
        // Define tmp path
        $tmpPath = sprintf('%s/tmp/tmp-%s', $this->basePath, uniqid());

        // Unarchive zip file
        $zip = new ZipArchive;
        $zip->open($path);
        $zip->extractTo($tmpPath);
        $zip->close();

        // Link extracted directory to destination
        $directories = File::directories($tmpPath);
        File::link($directories[0], $destination);
    }

    /**
     * Apply the file diff to the project
     */
    protected function applyFileDiff(array $file, string $path)
    {
        // Skip github files [Hotfix]
        if (str_starts_with($file['filename'], '.github')) {
            return;
        }

        // Define source and destination paths
        $source = sprintf('%s/%s', $path, $file['filename']);
        $destination = base_path($file['filename']);

        // If destination file exists, delete it
        if (File::exists($destination)) {
            File::delete($destination);
        }

        // If file is removed, skip
        if ($file['status'] === 'removed') {
            return;
        }

        // Ensure directory exists
        File::ensureDirectoryExists(dirname($destination));

        // Copy file to destination
        File::copy($source, $destination);
    }

    /**
     * Clean up the temporary files
     */
    protected function cleanUp()
    {
        // Delete tmp directory
        File::deleteDirectory($this->basePath . '/tmp');
    }
}
