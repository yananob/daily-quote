<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class MyTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load environment variables from .env.testing
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.testing');
        $dotenv->load();
    }
}
