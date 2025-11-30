<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\QuoteList;
use App\Quote;

class QuoteListTest extends TestCase
{
    public function testGetRandomQuote()
    {
        $quoteList = new QuoteList();
        $quote = $quoteList->getRandomQuote();

        if ($quote !== null) {
            $this->assertInstanceOf(Quote::class, $quote);
        } else {
            $this->assertNull($quote);
        }
    }
}
