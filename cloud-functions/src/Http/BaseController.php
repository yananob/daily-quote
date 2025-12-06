<?php

declare(strict_types=1);

namespace App\Http;

use eftec\bladeone\BladeOne;

use Gt\Csrf\TokenStore;

abstract class BaseController
{
    protected BladeOne $blade;
    protected TokenStore $tokenStore;

    public function __construct(TokenStore $tokenStore = null)
    {
        $views = __DIR__ . '/../../views';
        $cache = './tmp/cache';
        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        if ($tokenStore) {
            $this->tokenStore = $tokenStore;
            $this->blade->share('csrf', $this->tokenStore->getToken());
        }
    }
}
