<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Dotenv\Dotenv;

class TestCase extends PHPUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load environment variables from .env.testing
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env.testing');
        $dotenv->load();
    }
}
