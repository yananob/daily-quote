<?php
declare(strict_types=1);

namespace App\Http;

use App\AppConfig;
use eftec\bladeone\BladeOne;

abstract class BaseController
{
    protected BladeOne $blade;

    public function __construct()
    {
        $views = __DIR__ . '/../../views';
        $cache = __DIR__ . '/../../cache';
        if (!is_dir($cache)) {
            mkdir($cache, 0755, true);
        }
        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        $this->blade->share('basePath', AppConfig::getBasePath());
    }
}
