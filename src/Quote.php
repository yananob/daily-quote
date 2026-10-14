<?php

declare(strict_types=1);

namespace App;

/**
 * 格言ドメインモデルクラス。
 */
class Quote
{
    /**
     * @var array<string, mixed> 格言データ
     */
    private array $data;

    /**
     * @param array<string, mixed> $data 格言の属性配列
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * 配信用にフォーマットされたメッセージ文字列を取得します。
     *
     * @return string
     */
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

    /**
     * 格言番号（No）を取得します。
     *
     * @return string
     */
    public function getNo(): string
    {
        return (string)($this->data['no'] ?? '');
    }

    /**
     * 格言メッセージ本文を取得します。
     *
     * @return string
     */
    public function getMessage(): string
    {
        return (string)($this->data['message'] ?? '');
    }

    /**
     * 著者（発言者）を取得します。
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return (string)($this->data['author'] ?? '');
    }

    /**
     * 出典名を取得します。
     *
     * @return string
     */
    public function getSource(): string
    {
        return (string)($this->data['source'] ?? '');
    }

    /**
     * 出典リンクURLを取得します。
     *
     * @return string
     */
    public function getSourceLink(): string
    {
        return (string)($this->data['source_link'] ?? '');
    }

    /**
     * 配信回数を取得します。
     *
     * @return int
     */
    public function getDeliveredCount(): int
    {
        return (int)($this->data['delivered_count'] ?? 0);
    }
}
