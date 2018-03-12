<?php

namespace Superbalist\LaravelGoogleCloudStorage;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class GoogleCloudStorageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $factory = $this->app->make('filesystem'); /* @var FilesystemManager $factory */
        $factory->extend('gcs', function ($app, $config) {


            $storageClientSettings = [
                'projectId' => $config['project_id'],
            ];

            if (array_get($config, 'key_file_path')) {
                $storageClientSettings['keyFilePath'] = array_get($config, 'key_file_path');
            } else {
                $storageClientSettings['keyFile'] = array_get($config, 'key_file');
            }

            $storageClient = new StorageClient($storageClientSettings);


            $bucket = $storageClient->bucket($config['bucket']);
            $pathPrefix = array_get($config, 'path_prefix');
            $storageApiUri = array_get($config, 'storage_api_uri');

            $adapter = new GoogleStorageAdapter($storageClient, $bucket, $pathPrefix, $storageApiUri);

            return new Filesystem($adapter);
        });
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        //
    }
}
