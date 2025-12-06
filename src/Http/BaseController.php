<?php

declare(strict_types=1);

namespace App\Http;

use eftec\bladeone\BladeOne;

abstract class BaseController
{
    protected BladeOne $blade;

    public function __construct()
    {
        $views = __DIR__ . '/../../views';
        $cache = './tmp/cache';
        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
    }
}
