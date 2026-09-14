<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\QuoteList;
use App\Quote;
use Google\Cloud\Firestore\CollectionReference;
use Google\Cloud\Firestore\DocumentSnapshot;
use Google\Cloud\Firestore\Query;

class QuoteListTest extends TestCase
{
    private $quotesCollectionMock;
    private $quoteList;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quotesCollectionMock = $this->createMock(CollectionReference::class);
        $this->quoteList = new QuoteList($this->quotesCollectionMock);
    }

    public function testGetRandomQuote()
    {
        $snapshot = $this->createMock(DocumentSnapshot::class);
        $snapshot->method('exists')->willReturn(true);
        $snapshot->method('id')->willReturn('1');
        $snapshot->method('data')->willReturn([
            'author' => 'Author 1',
            'message' => 'Message 1',
            'delivered_count' => 0,
        ]);

        $this->quotesCollectionMock->expects($this->once())
            ->method('documents')
            ->willReturn([$snapshot]);

        $quote = $this->quoteList->getRandomQuote();

        $this->assertInstanceOf(Quote::class, $quote);
        $this->assertEquals('1', $quote->getNo());
        $this->assertEquals('Author 1', $quote->getAuthor());
    }

    public function test_合計件数を取得できること()
    {
        $snapshot1 = $this->createMock(DocumentSnapshot::class);
        $snapshot1->method('exists')->willReturn(true);
        $snapshot2 = $this->createMock(DocumentSnapshot::class);
        $snapshot2->method('exists')->willReturn(true);

        $this->quotesCollectionMock->expects($this->once())
            ->method('documents')
            ->willReturn(new \ArrayIterator([$snapshot1, $snapshot2]));

        $count = $this->quoteList->getTotalCount();

        $this->assertEquals(2, $count);
    }

    public function test_ページ指定でリストを取得できること()
    {
        $snapshot = $this->createMock(DocumentSnapshot::class);
        $snapshot->method('exists')->willReturn(true);
        $snapshot->method('id')->willReturn('1');
        $snapshot->method('data')->willReturn([
            'author' => 'Author 1',
            'message' => 'Message 1',
        ]);

        $queryMock = $this->createMock(Query::class);
        $queryMock->method('orderBy')->willReturnSelf();
        $queryMock->method('limit')->willReturnSelf();
        $queryMock->method('offset')->willReturnSelf();
        $queryMock->method('documents')->willReturn([$snapshot]);

        $this->quotesCollectionMock->expects($this->once())
            ->method('orderBy')
            ->with('no')
            ->willReturn($queryMock);

        $quotes = $this->quoteList->getListInPage(1);

        $this->assertNotEmpty($quotes);
        $this->assertContainsOnlyInstancesOf(Quote::class, $quotes);
    }
}
