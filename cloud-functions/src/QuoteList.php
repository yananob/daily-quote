<?php

declare(strict_types=1);

namespace App;

use Google\Cloud\Firestore\CollectionReference;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\FieldPath;

class QuoteList
{
    private CollectionReference $rootCollection;
    private CollectionReference $quotesCollection;

    public function __construct()
    {
        $firestore = new FirestoreClient();
        $this->rootCollection = $firestore->collection($_ENV['FIRESTORE_ROOT_COLLECTION']);
        $this->quotesCollection = $this->rootCollection->document('quotes')->collection('quotes');
    }

    public function getRandomQuote(): Quote
    {
        $documents = $this->quotesCollection->documents();

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

    public function getQuotes(int $page = 1, int $limit = 20): array
    {
        $query = $this->quotesCollection
            ->orderBy(FieldPath::documentId())
            ->limit($limit + 1) // 1件多く取得して次のページの存在を確認
            ->offset(($page - 1) * $limit);
        $documents = $query->documents();

        $quotes = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['no'] = $document->id();
                $quotes[] = new Quote($data);
            }
        }

        return $quotes;
    }
}
