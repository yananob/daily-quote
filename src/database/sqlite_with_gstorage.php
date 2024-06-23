<?php

use Google\Cloud\Storage\StorageClient;

final class SQLiteWithGStorage
{
    const FILENAME = 'database.sqlite';
    const BUCKET_NAME = 'daily-quote';

    public static function initialize(): void
    {
        // memo: まだLaravelが立ち上がってないためか、envでは環境変数が取れない
        // （コンテナじゃない場合もエラーが出ないが、この場合はgcloud configの情報を取ってそう）
        $client = new StorageClient([
            'keyFile' => json_decode(file_get_contents(config_path('gcp_serviceaccount.json')), true)
        ]);
        $client = new StorageClient();
        $bucket = $client->bucket(self::BUCKET_NAME);
        $object = $bucket->object(self::FILENAME);
        $object->downloadToFile(database_path(self::FILENAME));
    }
    
    public static function store(): void
    {
        $client = new StorageClient();
        $bucket = $client->bucket(self::BUCKET_NAME);
        $bucket->upload(
            fopen(database_path(self::FILENAME), 'r')
        );
        exit;
    }
}
