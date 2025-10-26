<?php

use Google\CloudFunctions\FunctionsFramework;
use App\PlainPHP\AuthController;
use App\PlainPHP\QuoteController;

require __DIR__.'/../vendor/autoload.php';

// Register the function with the Functions Framework.
FunctionsFramework::http('app', 'handle');

// The function that will be executed for each request.
function handle()
{
    // Simple router
    $path = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    if (isset($_POST['_method'])) {
        $method = strtoupper($_POST['_method']);
    }

    // Auth middleware
    if (strpos($path, '/login') !== 0 && !isset($_COOKIE['auth_token'])) {
        header('Location: /login');
        exit;
    }

    // Routing
    if ($path === '/login' && $method === 'GET') {
        (new AuthController())->showLoginForm();
    } elseif ($path === '/login' && $method === 'POST') {
        (new AuthController())->login();
    } elseif ($path === '/logout' && $method === 'POST') {
        (new AuthController())->logout();
    } elseif ($path === '/' && $method === 'GET') {
        (new QuoteController())->index();
    } elseif ($path === '/quotes' && $method === 'POST') {
        (new QuoteController())->store();
    } elseif ($path === '/quotes/create' && $method === 'GET') {
        (new QuoteController())->create();
    } elseif (preg_match('/^\/quotes\/([a-zA-Z0-9_]+)\/edit$/', $path, $matches) && $method === 'GET') {
        (new QuoteController())->edit($matches[1]);
    } elseif (preg_match('/^\/quotes\/([a-zA-Z0-9_]+)$/', $path, $matches) && $method === 'PATCH') {
        (new QuoteController())->update($matches[1]);
    } elseif (preg_match('/^\/quotes\/([a-zA-Z0-9_]+)$/', $path, $matches) && $method === 'DELETE') {
        (new QuoteController())->destroy($matches[1]);
    } else {
        http_response_code(404);
        echo 'Not Found';
    }
}