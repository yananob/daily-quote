<?php

declare(strict_types=1);

/**
 * ローカル環境用のBootstrapファイル。APP_ENVをセットする。
 */

$environment = $_ENV['APP_ENV'] ?? 'local';

// Composerのオートローダーはテスト時にも必要
require dirname(__DIR__) . '/vendor/autoload.php';

// Functions Frameworkのrouter.phpに処理を渡す
require_once __DIR__ . '/../vendor/google/cloud-functions-framework/router.php';
