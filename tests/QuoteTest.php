<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Quote;

class QuoteTest extends TestCase
{
    public function test_メッセージが正しくフォーマットされること()
    {
        $testData = [
            'no' => '1',
            'message' => 'テストメッセージです。',
            'author' => 'テスト著者',
            'source' => 'テスト出典',
            'source_link' => 'https://example.com'
        ];
        $quote = new Quote($testData);

        $expected = <<<EOF
        Quote of the day #1:

        テストメッセージです。

        [テスト著者] テスト出典 https://example.com
        EOF;

        $this->assertEquals($expected, $quote->getFormattedMessage());
    }
}
