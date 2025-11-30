<?php

declare(strict_types=1);

namespace Tests;

use App\QuoteList;
use App\Quote;

class QuoteListTest extends MyTestCase
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
