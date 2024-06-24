<?php

namespace App\Providers;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        \Illuminate\Pagination\Paginator::useBootstrap();

        print("env: " . env('APP_ENV') . "\n");
        // if (env('APP_ENV') === 'production') {
        $url->forceScheme('https');

        $this->__registerStoreDatabaseFunction();
        $this->__retrieveDatabase();
        // }
    }

    private function __retrieveDatabase(): void
    {
        print("Retrieving database\n");
        $bucketName = env('MYAPP_CLOUD_BUCKET_NAME');
        $dbFilename = env('MYAPP_SQLITE_FILENAME');
        if (empty($bucketName) || empty($dbFilename)) {
            throw new \Exception('Please specify MYAPP_CLOUD_BUCKET_NAME and MYAPP_SQLITE_FILENAME.');
        }

        $client = new StorageClient([
            // 'keyFile' => json_decode(file_get_contents(config_path('gcp_serviceaccount.json')), true)
        ]);
        $client = new StorageClient();
        $bucket = $client->bucket($bucketName);
        $object = $bucket->object($dbFilename);
        $object->downloadToFile(database_path($dbFilename));
        print("Database retrieved.\n");
    }

    private function __registerStoreDatabaseFunction(): void
    {
        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, function () {
            print("Received SIGTERM\n");
            print("Storing database\n");
            $this->__storeDatabase();
            print("Database stored.\n");
            exit;
        });
        printf("Registered event.\n");
    }

    private function __storeDatabase(): void
    {
        $client = new StorageClient([
            // 'keyFile' => json_decode(file_get_contents(config_path('gcp_serviceaccount.json')), true)
        ]);
        $bucket = $client->bucket(env('MYAPP_CLOUD_BUCKET_NAME'));
        $bucket->upload(
            fopen(database_path(env('MYAPP_SQLITE_FILENAME')), 'r')
        );
    }
}
