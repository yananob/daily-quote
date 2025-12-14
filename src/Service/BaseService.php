<?php

declare(strict_types=1);

namespace App\Service;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class BaseService
{
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('Service');
        $this->logger->pushHandler(new StreamHandler('php://stderr'));
    }
}

