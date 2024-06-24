<?php

namespace App\Providers;

use Google\Cloud\Storage\StorageClient;
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
    public function boot()
    {
        \Illuminate\Pagination\Paginator::useBootstrap();

        // $this->__retrieveDatabase();
    }

    // private function __retrieveDatabase(): void
    // {
    //     // memo: まだLaravelが立ち上がってないためか、envでは環境変数が取れない
    //     // （コンテナじゃない場合もエラーが出ないが、この場合はgcloud configの情報を取ってそう）
    //     $client = new StorageClient([
    //         'keyFile' => json_decode(file_get_contents(config_path('gcp_serviceaccount.json')), true)
    //     ]);
    //     $client = new StorageClient();
    //     $bucket = $client->bucket(self::BUCKET_NAME);
    //     $object = $bucket->object(self::FILENAME);
    //     $object->downloadToFile(database_path(self::FILENAME));
    // }
}
