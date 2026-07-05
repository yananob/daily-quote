<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\QuoteList;
use App\Quote;

class QuoteListTest extends TestCase
{
    private $quoteList;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quoteList = new QuoteList();
    }

    public function testGetRandomQuote()
    {
        $quote = $this->quoteList->getRandomQuote();

        $this->assertInstanceOf(Quote::class, $quote);
    }

    public function test_統計情報を取得できること()
    {
        $quoteList = new QuoteList();
        $stats = $quoteList->getStatistics();

        $this->assertArrayHasKey('total_quotes', $stats);
        $this->assertArrayHasKey('total_delivered', $stats);
        $this->assertArrayHasKey('average_delivered', $stats);
        $this->assertGreaterThanOrEqual(0, $stats['total_quotes']);
    }

    public function test_ページ指定でリストを取得できること()
    {
        $quoteList = new QuoteList();
        $quotes = $quoteList->getListInPage(1);

        $this->assertNotEmpty($quotes);
        $this->assertContainsOnlyInstancesOf(Quote::class, $quotes);
    }
}
