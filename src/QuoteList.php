<?php

declare(strict_types=1);

namespace App;

use App\Service\FirestoreService;
use Carbon\Carbon;
use Google\Cloud\Firestore\CollectionReference;
use Google\Cloud\Firestore\FieldValue;

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

    /**
     * 配信回数が最小の格言の中からランダムに1件取得します。
     *
     * @return Quote 選択された格言オブジェクト
     * @throws \Exception 登録されている格言が存在しない場合
     */
    public function getRandomQuote(): Quote
    {
        $documents = $this->quotesCollection->documents();

        $allQuotes = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['no'] = $document->id();
                $data['delivered_count'] = (int)($data['delivered_count'] ?? 0);
                $allQuotes[] = $data;
            }
        }

        if (empty($allQuotes)) {
            throw new \Exception('登録されている格言がありません。');
        }

        // 配信回数が最小のものを抽出
        $minCount = min(array_column($allQuotes, 'delivered_count'));
        $candidates = array_values(array_filter($allQuotes, fn($q) => $q['delivered_count'] === $minCount));

        // 常に同じ順序になるよう 'no' でソート
        usort($candidates, fn($a, $b) => (int)$a['no'] <=> (int)$b['no']);

        // 日本時間の年月日、時分秒、マイクロ秒をシードとして使用する
        $date = Carbon::now('Asia/Tokyo');
        $seedString = $date->format('YmdHisu');
        $seed = crc32($seedString);
        mt_srand($seed);

        $randomIndex = mt_rand(0, count($candidates) - 1);
        $randomQuote = $candidates[$randomIndex];

        return new Quote($randomQuote);
    }

    /**
     * ページ指定で格言一覧を取得します。
     *
     * @param int $page ページ番号（1から開始）
     * @param int $limit 1ページあたりの表示件数
     * @return Quote[]
     */
    public function getListInPage(int $page = 1, int $limit = 20): array
    {
        $query = $this->quotesCollection
            ->orderBy('no')
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

    /**
     * 全格言の統計情報（総件数、総配信数、平均配信数）を取得します。
     *
     * @return array{totalQuotes: int, totalDelivered: int, averageDelivered: float}
     */
    public function getStatistics(): array
    {
        $documents = $this->quotesCollection->documents();
        $totalQuotes = 0;
        $totalDelivered = 0;

        foreach ($documents as $document) {
            if ($document->exists()) {
                $totalQuotes++;
                $totalDelivered += (int)($document->data()['delivered_count'] ?? 0);
            }
        }

        $averageDelivered = $totalQuotes > 0 ? $totalDelivered / $totalQuotes : 0;

        return [
            'totalQuotes' => $totalQuotes,
            'totalDelivered' => $totalDelivered,
            'averageDelivered' => round($averageDelivered, 2),
        ];
    }

    /**
     * 全格言の総数を取得します。
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        $documents = $this->quotesCollection->documents();
        return iterator_count($documents);
    }

    /**
     * ID（格言番号）から格言を取得します。
     *
     * @param int $id
     * @return Quote|null
     */
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

    /**
     * 格言の配信回数を1増やします。
     *
     * @param int $id
     * @return void
     */
    public function incrementDeliveredCount(int $id): void
    {
        $this->quotesCollection->document((string)$id)->update([
            ['path' => 'delivered_count', 'value' => FieldValue::increment(1)]
        ]);
    }

    /**
     * 既存の格言情報を更新します。
     *
     * @param int $id
     * @param array{author: string, message: string, source?: string, source_link?: string} $data
     * @return void
     */
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

    /**
     * 新しい格言を登録します。自動で最大No+1が採番されます。
     *
     * @param array{author: string, message: string, source?: string, source_link?: string} $data
     * @return void
     */
    public function create(array $data): void
    {
        // 1. 最新の最大Noを取得
        $query = $this->quotesCollection->orderBy('no', 'DESC')->limit(1);
        $documents = $query->documents();

        $lastNo = 0;
        foreach ($documents as $document) {
            if ($document->exists()) {
                $lastNo = (int)$document->data()['no'];
            }
        }

        // 2. 新しいNoを計算
        $newNo = $lastNo + 1;

        // 3. 新しいDocumentを作成
        $newDocument = $this->quotesCollection->document((string)$newNo);

        $newDocument->set([
            'no' => $newNo,
            'author' => $data['author'],
            'message' => $data['message'],
            'source' => $data['source'] ?? '',
            'source_link' => $data['source_link'] ?? '',
            'delivered_count' => 0,
        ]);
    }

    /**
     * 格言を削除します。
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->quotesCollection->document((string)$id)->delete();
    }
}
