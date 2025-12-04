<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;
use CloudEvents\V1\CloudEventInterface;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use LINE\Clients\MessagingApi\Model\PushMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use App\QuoteList;
use App\Http\QuotesController;

function initialize(): void
{
    $environment = $_ENV['APP_ENV'] ?? 'testing';

    $file_to_load = ['.env'];   // デフォルトは .env
    if ($environment !== 'local') {
        array_unshift($file_to_load, ".env.{$environment}");
    }
    $dotenv = Dotenv::createImmutable(__DIR__, $file_to_load);
    $dotenv->load();
    $dotenv->required(['LINE_BOT_CHANNEL_ACCESS_TOKEN', 'LINE_DELIVER_TARGET'])->notEmpty();

    $_ENV['APP_ENV'] = $environment;
    // var_dump($_ENV);
}
initialize();

FunctionsFramework::http('main_http', 'main_http');
function main_http(ServerRequestInterface $request)
{
    $log = new Logger('main_http');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function triggered with ' . $_ENV['APP_ENV'] . ' environment.');

    $controller = new QuotesController();

    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    $log->info("{$method} {$path}");

    // very simple routing
    if ($method === 'GET' && preg_match('#^/quotes/edit/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $controller->edit($request, $id);
    } elseif ($method === 'POST' && preg_match('#^/quotes/update/(\d+)$#', $path, $matches)) {
        $id = (int)$matches[1];
        return $controller->update($request, $id);
    } elseif ($method === 'GET' && $path === '/') {
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
    $log->info('Function triggered.');

    $client = new \GuzzleHttp\Client();
    $config = new \LINE\Clients\MessagingApi\Configuration();
    $config->setAccessToken($_ENV['LINE_BOT_CHANNEL_ACCESS_TOKEN']);
    $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
        client: $client,
        config: $config,
    );

    $lineDeliverTarget = $_ENV['LINE_DELIVER_TARGET'];

    $quote = (new QuoteList())->getRandomQuote();

    $message = $quote->getFormattedMessage();
    $log->info("Selected quote: {$message}");

    $message = new TextMessage(['text' => $message]);
    $request = new PushMessageRequest([
        'to' => $lineDeliverTarget,
        'messages' => [$message],
    ]);
    $response = $messagingApi->pushMessage($request);

    // if ($response->isSucceeded()) {
    $log->info('Message sent successfully!');
    // } else {
    //     $log->error('Failed to send message: ' . $response->getRawBody());
    // }
}
