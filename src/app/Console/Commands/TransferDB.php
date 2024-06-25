<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Cloud\Storage\StorageClient;

class TransferDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:transfer-db {method}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store or restore database with Cloud Storage.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $method = $this->argument('method');
        print("TransferDB method: {$method}\n");
        if ($method === 'restore') {
            $this->__restoreDatabase();
        } elseif ($method === 'store') {
            $this->__storeDatabase();
        } else {
            throw new \Exception("Unknown method: {$method}");
        }
        return 0;
    }

    private function __restoreDatabase(): void
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
