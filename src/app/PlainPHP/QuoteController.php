<?php

namespace App\PlainPHP;

use App\Models\Quote;
use Google\Cloud\Firestore\FirestoreClient;

require_once __DIR__ . '/../../config.php';

class QuoteController
{
    const COUNT_PER_PAGE = 10;

    private $firestore;
    private $collection;

    public function __construct()
    {
        $this->firestore = new FirestoreClient([
            'projectId' => config('google_cloud_project'),
        ]);
        $this->collection = $this->firestore->collection('quotes');
    }

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $cursor = $_GET['cursor'] ?? null;
        $prevCursor = $_GET['prev_cursor'] ?? null;

        $query = $this->collection->orderBy('message');

        if (!empty($keyword)) {
            $query = $query->where('message', '>=', $keyword)->where('message', '<=', $keyword . "\uf8ff");
        }

        if ($cursor) {
            $query = $query->startAfter([$cursor]);
        } elseif ($prevCursor) {
            $query = $query->endBefore([$prevCursor])->limitToLast(self::COUNT_PER_PAGE);
        } else {
            $query = $query->limit(self::COUNT_PER_PAGE);
        }

        $documents = $query->documents();
        $quotes = [];
        $nextCursor = null;
        $prevCursor = null;
        $firstDoc = null;
        $lastDoc = null;

        foreach ($documents as $document) {
            if (!$firstDoc) {
                $firstDoc = $document;
            }
            $lastDoc = $document;
            $quote = new Quote($document->data());
            $quote->id = $document->id();
            $quotes[] = $quote;
        }

        if ($lastDoc) {
            $nextCursor = $lastDoc->data()['message'];
        }
        if ($firstDoc) {
            $prevCursor = $firstDoc->data()['message'];
        }


        require __DIR__ . '/../../../resources/views/quotes/index.php';
    }

    public function create()
    {
        $quote = new Quote();
        require __DIR__ . '/../../../resources/views/quotes/create.php';
    }

    public function store()
    {
        $data = [
            'message' => $_POST['message'],
            'author' => $_POST['author'],
            'source' => $_POST['source'],
            'source_link' => $_POST['source_link'],
        ];
        $this->collection->add($data);
        header('Location: /');
        exit;
    }

    public function edit($id)
    {
        $document = $this->collection->document($id)->snapshot();
        $quote = new Quote($document->data());
        $quote->id = $document->id();
        require __DIR__ . '/../../../resources/views/quotes/edit.php';
    }

    public function update($id)
    {
        $data = [
            'message' => $_POST['message'],
            'author' => $_POST['author'],
            'source' => $_POST['source'],
            'source_link' => $_POST['source_link'],
        ];
        $this->collection->document($id)->set($data, ['merge' => true]);
        header('Location: /');
        exit;
    }

    public function destroy($id)
    {
        $this->collection->document($id)->delete();
        header('Location: /');
        exit;
    }
}