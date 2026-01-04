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
            $gcpServiceAccount = json_decode(getenv('FIREBASE_SERVICE_ACCOUNT'), true);
            if ($gcpServiceAccount) {
                self::$client = new FirestoreClient(
                    [
                        'keyFile' => $gcpServiceAccount,
                    ]
                );
            } else {
                self::$client = new FirestoreClient();
            }
        }

        return self::$client;
    }
}
