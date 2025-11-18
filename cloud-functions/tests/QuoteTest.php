<?php

use PHPUnit\Framework\TestCase;
use App\Quote;

class QuoteTest extends TestCase
{
    public function testGetRandomMessage()
    {
        $quote = new Quote();
        $message = $quote->getRandomMessage();

        $this->assertIsString($message);
        $this->assertNotEmpty($message);
    }
}
