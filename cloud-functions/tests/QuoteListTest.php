<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\QuoteList;
use App\Quote;

class QuoteListTest extends TestCase
{
    public function testGetRandomQuote()
    {
        $quoteList = new QuoteList();
        $quote = $quoteList->getRandomQuote();

        $this->assertInstanceOf(Quote::class, $quote);
    }
}
