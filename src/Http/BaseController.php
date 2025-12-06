<?php

declare(strict_types=1);

namespace App\Http;

use eftec\bladeone\BladeOne;
use App\AppConfig;

abstract class BaseController
{
    protected BladeOne $blade;

    public function __construct()
    {
        $views = __DIR__ . '/../../views';
        $cache = './tmp/cache';

        if (!is_dir($cache)) {
            mkdir($cache, 0777, true);
        }
        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        $this->blade->share('basePath', AppConfig::getBasePath());
    }
}
