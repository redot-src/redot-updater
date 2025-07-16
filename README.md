# Redot Updater

🚨 **Experimental Package** - This package is currently in experimental status. Use with caution in production environments.

A Laravel package that provides seamless integration with [Redot.dev](https://redot.dev) dashboard scaffold. Keep your Redot-based Laravel project up to date with the latest scaffold improvements and features.

## About

Redot Updater is a command-line tool designed to help you maintain and update your Redot dashboard scaffold. It connects to the Redot platform to sync the latest updates, preview changes, and manage your project's evolution over time.

## Requirements

- PHP ^8.1
- Laravel ^10.0|^11.0|^12.0

## Installation

Install the package via Composer:

```bash
composer require redot/updater
```

The package will automatically register its service provider via Laravel's package auto-discovery.

## Usage

The package provides four main commands to manage your Redot dashboard:

### 1. Login to Redot

Authenticate with your Redot account and get your project token and slug:

```bash
php artisan redot:login
```

This command will prompt you for your credentials and store the necessary authentication tokens for subsequent operations.

### 2. Logout

Clear your stored authentication credentials:

```bash
php artisan redot:logout
```

### 3. Preview Changes

Generate a URL to preview the differences between your current project and the latest scaffold:

```bash
php artisan redot:diff
```

This command outputs a URL that you can visit on [Redot.dev](https://redot.dev) to review the changes before applying them.

### 4. Update Project

Update your project to the latest Redot scaffold:

```bash
php artisan redot:update
```

This command will download and apply the latest updates from the Redot scaffold to your project.

**Commit Changes**

Before running the update command, you should commit all your changes and review each file manually to make sure you're not missing anything, as the update command will overwrite your files with the latest scaffold.

## Limitations

⚠️ **Important Limitation**: The updater currently **cannot update files in the `.github` directory**. You will need to manually manage any changes to GitHub workflow files, actions, or other `.github` directory contents.

## Contributing

This is an experimental package. Contributions are welcome, but please note that the API and functionality may change significantly in future versions.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Disclaimer

This package is experimental and should be used with caution. Always backup your project before running update commands. The package maintainers are not responsible for any data loss or issues that may occur during the update process.
