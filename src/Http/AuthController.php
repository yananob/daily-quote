<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use App\Service\AuthService;
use App\AppConfig;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct(AuthService $authService = null)
    {
        parent::__construct();
        $this->authService = $authService ?? new AuthService();
    }

    public function showLoginForm(): ResponseInterface
    {
        $body = $this->blade->run('auth.login');
        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $password = $data['password'] ?? '';

        if ($this->authService->attempt($password)) {
            return new Response(302, ['Location' => AppConfig::getBasePath() . '/']);
        } else {
            $body = $this->blade->run('auth.login', [
                'error' => 'Invalid password.',
            ]);
            return new Response(401, ['Content-Type' => 'text/html'], $body);
        }
    }

    public function logout(): ResponseInterface
    {
        $this->authService->logout();
        return new Response(302, ['Location' => AppConfig::getBasePath() . '/login']);
    }
}
