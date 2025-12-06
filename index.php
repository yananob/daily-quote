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

function initialize(): void
{
    // $environment = $_ENV['APP_ENV'] ?? 'testing';

    // $file_to_load = ['.env'];   // デフォルトは .env
    // if ($environment !== 'local') {
    //     array_unshift($file_to_load, ".env.{$environment}");
    // }
    // // $dotenv = Dotenv::createImmutable(__DIR__, $file_to_load);
    // // $dotenv->load();
    // // $dotenv->required(['FIREBASE_CONFIG', 'LINE_TOKENS_N_TARGETS', 'LINE_DELIVER_TARGET'])->notEmpty();

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

    $controller = new QuotesController();

    $basePath = AppConfig::getBasePath();
    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    $log->info("{$method} {$path}");

    // very simple routing
    if ($method === 'GET' && preg_match('#^' . $basePath . '/quotes/edit/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $controller->edit($request, $id);
    } elseif ($method === 'POST' && preg_match('#^' . $basePath . '/quotes/update/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $controller->update($request, $id);
    } elseif ($method === 'GET' && $path === $basePath . '/quotes/new') {
        return $controller->new($request);
    } elseif ($method === 'POST' && $path === $basePath . '/quotes/store') {
        return $controller->store($request);
    } elseif ($method === 'POST' && preg_match('#^' . $basePath . '/quotes/delete/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $controller->delete($request, $id);
    } elseif ($method === 'GET' && ($path === $basePath || $path === $basePath . '/')) {
        return $controller->index($request);
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

    $quote = (new QuoteList())->getRandomQuote();

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
    // } else {
    //     $log->error('Failed to send message: ' . $response->getRawBody());
    // }
}
