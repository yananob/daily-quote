<?php

declare(strict_types=1);

/**
 * ローカル環境用のBootstrapファイル。APP_ENVをセットする。
 */

// $environment = $getenv('APP_ENV') ?? 'local';
putenv('APP_ENV=local');

// // Composerのオートローダーはテスト時にも必要
// require dirname(__DIR__) . '/vendor/autoload.php';

// // Functions Frameworkのrouter.phpに処理を渡す
// require_once __DIR__ . '/../vendor/google/cloud-functions-framework/router.php';
require_once __DIR__ . '/../vendor/bin/router.php';
