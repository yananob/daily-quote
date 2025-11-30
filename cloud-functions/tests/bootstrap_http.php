<?php

declare(strict_types=1);

$environment = $_ENV['APP_ENV'] ?? 'testing'; 

// Composerのオートローダーはテスト時にも必要やで
require dirname(__DIR__) . '/vendor/autoload.php';

// Functions Frameworkのrouter.phpに処理を渡す
require_once __DIR__ . '/../vendor/google/cloud-functions-framework/router.php';
