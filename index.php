<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\AppConfig;
use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;
use CloudEvents\V1\CloudEventInterface;
// use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use LINE\Clients\MessagingApi\Model\PushMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use App\QuoteList;
use App\Http\QuotesController;
use App\Http\AuthController;
use App\Service\AuthService;

function initialize(): void
{
    // $environment = $_ENV['APP_ENV'] ?? 'testing';

    // $file_to_load = ['.env'];   // デフォルトは .env
    // if ($environment !== 'local') {
    //     array_unshift($file_to_load, ".env.{$environment}");
    // }
    // // $dotenv = Dotenv::createImmutable(__DIR__, $file_to_load);
    // // $dotenv->load();
    // // $dotenv->required(['FIREBASE_SERVICE_ACCOUNT', 'LINE_TOKENS_N_TARGETS', 'LINE_DELIVER_TARGET'])->notEmpty();

    // $_ENV['APP_ENV'] = $environment;
    // // var_dump($_ENV);
}
initialize();

FunctionsFramework::http('main_http', 'main_http');
function main_http(ServerRequestInterface $request)
{
    $log = new Logger('main_http');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function triggered with ' . AppConfig::getEnvironment() . ' environment.');

    $authService = new AuthService();
    $quotesController = new QuotesController();
    $authController = new AuthController($authService);

    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    $log->info("{$method} {$path}");

    // Public routes
    if ($method === 'GET' && $path === '/login') {
        return $authController->showLoginForm();
    } elseif ($method === 'POST' && $path === '/login') {
        return $authController->login($request);
    } elseif ($method === 'GET' && $path === '/logout') {
        return $authController->logout();
    }

    // Authentication check
    if (!$authService->isAuthenticated()) {
        return new \GuzzleHttp\Psr7\Response(302, ['Location' => AppConfig::getBasePath() . '/login']);
    }

    // Protected routes
    if ($method === 'GET' && preg_match('#^/quotes/edit/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $quotesController->edit($request, $id);
    } elseif ($method === 'POST' && preg_match('#^/quotes/update/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $quotesController->update($request, $id);
    } elseif ($method === 'GET' && $path === '/quotes/new') {
        return $quotesController->new($request);
    } elseif ($method === 'POST' && $path === '/quotes/store') {
        return $quotesController->store($request);
    } elseif ($method === 'POST' && preg_match('#^/quotes/delete/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $quotesController->delete($request, $id);
    } elseif ($method === 'GET' && $path === '/') {
        return $quotesController->index($request);
    } else {
        return new \GuzzleHttp\Psr7\Response(404, [], 'Not Found');
    }
}

FunctionsFramework::cloudEvent('main_event', 'main_event');
function main_event(CloudEventInterface $event): void
{
    $log = new Logger('main_event');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function triggered with ' . AppConfig::getEnvironment() . ' environment.');

    $client = new \GuzzleHttp\Client();
    $config = new \LINE\Clients\MessagingApi\Configuration();
    $lineDeliverTarget = AppConfig::getLineDeliverTarget();
    $lineConfig = json_decode(getenv('LINE_TOKENS_N_TARGETS'));
    $config->setAccessToken($lineConfig->tokens->$lineDeliverTarget);
    $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
        client: $client,
        config: $config,
    );

    $quoteList = new QuoteList();
    $quote = $quoteList->getRandomQuote();

    $message = $quote->getFormattedMessage();
    $log->info("Selected quote: {$message}");

    $message = new TextMessage(['text' => $message]);
    $request = new PushMessageRequest([
        'to' => $lineConfig->target_ids->$lineDeliverTarget,
        'messages' => [$message],
    ]);
    $response = $messagingApi->pushMessage($request);

    // if ($response->isSucceeded()) {
    $log->info('Message sent successfully!');
    $quoteList->incrementDeliveredCount((int)$quote->getNo());
    // } else {
    //     $log->error('Failed to send message: ' . $response->getRawBody());
    // }
}
