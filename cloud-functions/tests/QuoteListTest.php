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
        $originalMessage = $originalQuote->getMessage();
        $originalSource = $originalQuote->getSource();
        $originalSourceLink = $originalQuote->getSourceLink();

        $newAuthor = 'Updated Author';
        $newMessage = 'Updated Message';
        $newSource = 'Updated Source';
        $newSourceLink = 'http://updated.example.com';

        $this->quoteList->update($id, [
            'author' => $newAuthor,
            'message' => $newMessage,
            'source' => $newSource,
            'source_link' => $newSourceLink,
        ]);

        $updatedQuote = $this->quoteList->find($id);

        $this->assertEquals($newAuthor, $updatedQuote->getAuthor());
        $this->assertEquals($newMessage, $updatedQuote->getMessage());
        $this->assertEquals($newSource, $updatedQuote->getSource());
        $this->assertEquals($newSourceLink, $updatedQuote->getSourceLink());

        // Restore original data
        $this->quoteList->update($id, [
            'author' => $originalAuthor,
            'message' => $originalMessage,
            'source' => $originalSource,
            'source_link' => $originalSourceLink,
        ]);
    }
}
