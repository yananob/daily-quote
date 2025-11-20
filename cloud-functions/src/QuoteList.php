<?php

namespace App;

use Google\Cloud\Firestore\FirestoreClient;

class QuoteList
{
    private FirestoreClient $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient();
    }

    public function getRandomQuote(): ?Quote
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
            return null;
        }

        $randomQuote = $quotes[array_rand($quotes)];

        return new Quote($randomQuote);
    }
}
