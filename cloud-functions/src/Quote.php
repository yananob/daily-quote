<?php

namespace App;

use Google\Cloud\Firestore\FirestoreClient;

class Quote
{
    private FirestoreClient $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient();
    }

    public function getRandomMessage(): string
    {
        $collectionReference = $this->firestore->collection('quotes');
        $documents = $collectionReference->documents();

        $quotes = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $quotes[] = $document->data();
            }
        }

        if (empty($quotes)) {
            return 'No quotes found.';
        }

        $randomQuote = $quotes[array_rand($quotes)];

        return <<<EOF
        Quote of the day:

        {$randomQuote['message']}

        [{$randomQuote['author']}] {$randomQuote['source']} {$randomQuote['source_link']}
        EOF;
    }
}
