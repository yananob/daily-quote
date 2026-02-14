<?php

declare(strict_types=1);

namespace App;

use App\Service\FirestoreService;
use Google\Cloud\Firestore\CollectionReference;

class QuoteList
{
    private CollectionReference $rootCollection;
    private CollectionReference $quotesCollection;

    public function __construct()
    {
        $firestore = FirestoreService::getClient();
        $this->rootCollection = $firestore->collection(AppConfig::getFirestoreRootCollection());
        $this->quotesCollection = $this->rootCollection->document('quotes')->collection('quotes');
    }

    public function getRandomQuote(): Quote
    {
        $documents = $this->quotesCollection->documents();

        $quotes = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['no'] = (int)$document->id();
                $quotes[] = $data;
            }
        }

        if (empty($quotes)) {
            throw new \Exception("登録されている格言がありません。");
        }

        // 常に同じ順序になるよう 'no' でソート
        usort($quotes, fn($a, $b) => $a['no'] <=> $b['no']);

        // 日本時間の年月日、時分秒、マイクロ秒をシードとして使用する
        $tz = new \DateTimeZone('Asia/Tokyo');
        $date = new \DateTime('now', $tz);
        $seedString = $date->format('YmdHisu');
        $seed = crc32($seedString);
        mt_srand($seed);

        $randomIndex = mt_rand(0, count($quotes) - 1);
        $randomQuote = $quotes[$randomIndex];

        return new Quote($randomQuote);
    }

    public function getListInPage(int $page = 1, int $limit = 20): array
    {
        $query = $this->quotesCollection
            ->orderBy("no")
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

    public function getTotalCount(): int
    {
        $documents = $this->quotesCollection->documents();
        return iterator_count($documents);
    }

    public function find(int $id): ?Quote
    {
        $document = $this->quotesCollection->document((string)$id)->snapshot();

        if ($document->exists()) {
            $data = $document->data();
            $data['no'] = $document->id();
            return new Quote($data);
        }

        return null;
    }

    public function update(int $id, array $data): void
    {
        $this->quotesCollection->document((string)$id)->set(
            [
                'author' => $data['author'],
                'message' => $data['message'],
                'source' => $data['source'] ?? '',
                'source_link' => $data['source_link'] ?? '',
            ],
            ['merge' => true]
        );
    }

    public function create(array $data): void
    {
        // 1. Find the highest existing quote number.
        $query = $this->quotesCollection->orderBy('no', 'DESC')->limit(1);
        $documents = $query->documents();

        $lastNo = 0;
        foreach ($documents as $document) {
            if ($document->exists()) {
                $lastNo = (int)$document->data()['no'];
            }
        }

        // 2. Calculate the new quote number.
        $newNo = $lastNo + 1;

        // 3. Create a new document with the new number as ID.
        $newDocument = $this->quotesCollection->document((string)$newNo);

        $newDocument->set([
            'no' => $newNo,
            'author' => $data['author'],
            'message' => $data['message'],
            'source' => $data['source'] ?? '',
            'source_link' => $data['source_link'] ?? '',
        ]);
    }

    public function delete(int $id): void
    {
        $this->quotesCollection->document((string)$id)->delete();
    }
}
