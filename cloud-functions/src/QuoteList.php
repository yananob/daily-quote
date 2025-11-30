<?php

declare(strict_types=1);

namespace App;

use Google\Cloud\Firestore\CollectionReference;
use Google\Cloud\Firestore\FirestoreClient;

class QuoteList
{
    private FirestoreClient $firestore;
    private CollectionReference $rootCollection;

    public function __construct()
    {
        $this->firestore = new FirestoreClient();
        $this->rootCollection = $this->firestore->collection($_ENV['FIRESTORE_ROOT_COLLECTION']);
    }

    public function getRandomQuote(): Quote
    {
        $documents = $this->rootCollection->document("quotes")->collection("quotes")->documents();

        $quotes = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['no'] = $document->id();
                $quotes[] = $data;
            }
        }

        $randomQuote = $quotes[array_rand($quotes)];

        return new Quote($randomQuote);
    }
}
