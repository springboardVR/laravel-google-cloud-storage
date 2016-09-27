# laravel-google-cloud-storage

A Google Cloud Storage filesystem for Laravel.

[![Author](http://img.shields.io/badge/author-@superbalist-blue.svg?style=flat-square)](https://twitter.com/superbalist)
[![Build Status](https://img.shields.io/travis/Superbalist/laravel-google-cloud-storage/master.svg?style=flat-square)](https://travis-ci.org/Superbalist/laravel-google-cloud-storage)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/superbalist/laravel-google-cloud-storage.svg?style=flat-square)](https://packagist.org/packages/superbalist/laravel-google-cloud-storage)
[![Total Downloads](https://img.shields.io/packagist/dt/superbalist/laravel-google-cloud-storage.svg?style=flat-square)](https://packagist.org/packages/superbalist/laravel-google-cloud-storage)

This package is a wrapper bridging [flysystem-google-storage](https://github.com/Superbalist/flysystem-google-storage) into Laravel as an available storage disk.

## Installation

```bash
composer require superbalist/laravel-google-cloud-storage
```

Register the service provider in app.php
```php
'providers' => [
    // ...
    Superbalist\LaravelGoogleCloudStorage\GoogleCloudStorageServiceProvider::class,
]
```

Add a new disk to your `filesystems.php` config

```php
'gcs' => [
    'driver' => 'gcs',
    'project_id' => 'your-project-id',
    'key_file' => null, // optional: /path/to/service-account.json
    'bucket' => 'your-bucket',
    'path_prefix' => null, // optional: /default/path/to/apply/in/bucket
],
```

### Authentication

The Google Client uses a few methods to determine how it should authenticate with the Google API.

1. If you specify a `key_file` in the disk config, that json credentials file will be used.
2. If the `GOOGLE_APPLICATION_CREDENTIALS` env var is set, it will use that.
   ```php
   putenv('GOOGLE_APPLICATION_CREDENTIALS=/path/to/service-account.json');
   ```
3. It will then try load the key file from a 'well known path':
   * windows: %APPDATA%/gcloud/application_default_credentials.json
   * others: $HOME/.config/gcloud/application_default_credentials.json

4. If running in **Google App Engine**, the built-in service account associated with the application will be used.
5. If running in **Google Compute Engine**, the built-in service account associated with the virtual machine instance will be used.

## Usage

```php
$disk = Storage::disk('gcs');

// create a file
$disk->put('avatars/1', $fileContents);

// check if a file exists
$exists = $disk->exists('file.jpg');

// get file modification date
$time = $disk->lastModified('file1.jpg');

// copy a file
$disk->copy('old/file1.jpg', 'new/file1.jpg');

// move a file
$disk->move('old/file1.jpg', 'new/file1.jpg');

// see https://laravel.com/docs/5.3/filesystem for full list of available functionality
```