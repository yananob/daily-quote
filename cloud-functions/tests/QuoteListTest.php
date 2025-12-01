<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\QuoteList;
use App\Quote;

/**
 * @group integration
 */
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

    public function testFind()
    {
        // Assuming there's a quote with ID 1
        $quote = $this->quoteList->find(1);

        $this->assertInstanceOf(Quote::class, $quote);
        $this->assertEquals(1, $quote->getNo());
    }

    public function testUpdate()
    {
        $id = 1;
        $originalQuote = $this->quoteList->find($id);
        $originalAuthor = $originalQuote->getAuthor();
        $originalText = $originalQuote->getText();

        $newAuthor = 'Updated Author';
        $newText = 'Updated Text';

        $this->quoteList->update($id, [
            'author' => $newAuthor,
            'text' => $newText,
        ]);

        $updatedQuote = $this->quoteList->find($id);

        $this->assertEquals($newAuthor, $updatedQuote->getAuthor());
        $this->assertEquals($newText, $updatedQuote->getText());

        // Restore original data
        $this->quoteList->update($id, [
            'author' => $originalAuthor,
            'text' => $originalText,
        ]);
    }
}
