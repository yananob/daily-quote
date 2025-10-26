<?php

namespace App\Models;

use Google\Cloud\Firestore\FirestoreClient;

class Quote
{
    public $id;
    public $message;
    public $author;
    public $source;
    public $source_link;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->message = $data['message'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->source = $data['source'] ?? '';
        $this->source_link = $data['source_link'] ?? '';
    }

    public static function randomMessage(): string
    {
        $firestore = new FirestoreClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT'),
        ]);
        $collection = $firestore->collection('quotes');

        // Get the total count of documents.
        $count = $collection->documents()->size();

        if ($count === 0) {
            return "No quotes found.";
        }

        // Generate a random offset.
        $offset = rand(0, $count - 1);

        // Fetch a single random document.
        $documents = $collection->limit(1)->offset($offset)->documents();

        $quoteData = iterator_to_array($documents)[0]->data();
        $quote = new Quote($quoteData);
        $quote->id = iterator_to_array($documents)[0]->id();


        return <<<EOF
Quote of the day #{$quote->id}:

{$quote->message}

[{$quote->author}] {$quote->source} {$quote->source_link}
EOF;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $firestore = new FirestoreClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT'),
        ]);
        $document = $firestore->collection('quotes')->document($value)->snapshot();

        if ($document->exists()) {
            $quote = new Quote($document->data());
            $quote->id = $document->id();
            return $quote;
        }

        return null;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}