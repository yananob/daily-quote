<?php

declare(strict_types=1);

namespace App\Service;

use Google\Cloud\Firestore\FirestoreClient;

class FirestoreService
{
    private static ?FirestoreClient $client = null;

    public static function getClient(): FirestoreClient
    {
        if (self::$client === null) {
            self::$client = new FirestoreClient();
        }

        return self::$client;
    }
}
