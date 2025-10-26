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
            'projectId' => config('google_cloud_project'),
        ]);
        $collection = $firestore->collection('quotes');

        // Generate a random key
        $randomKey = $collection->document()->id();

        $query = $collection->where('__name__', '>=', $randomKey)->limit(1);
        $documents = $query->documents();

        if (iterator_count($documents) == 0) {
            $query = $collection->where('__name__', '<', $randomKey)->limit(1);
            $documents = $query->documents();
        }

        if (iterator_count($documents) == 0) {
            return "No quotes found.";
        }

        $document = iterator_to_array($documents)[0];
        $quoteData = $document->data();
        $quote = new Quote($quoteData);
        $quote->id = $document->id();


        return <<<EOF
Quote of the day #{$quote->id}:

{$quote->message}

[{$quote->author}] {$quote->source} {$quote->source_link}
EOF;
    }
}