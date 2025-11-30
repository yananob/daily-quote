<?php

declare(strict_types=1);

namespace App;

class Quote
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getFormattedMessage(): string
    {
        $no = $this->data['no'] ?? '';
        $message = $this->data['message'] ?? '';
        $author = $this->data['author'] ?? '';
        $source = $this->data['source'] ?? '';
        $sourceLink = $this->data['source_link'] ?? '';

        return <<<EOF
        Quote of the day #{$no}:

        {$message}

        [{$author}] {$source} {$sourceLink}
        EOF;
    }

    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    public function getAuthor(): string
    {
        return $this->data['author'] ?? '';
    }
}
