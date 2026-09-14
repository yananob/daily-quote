<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\AppConfig;
use App\Http\QuotesController;
use App\QuoteList;
use CloudEvents\V1\CloudEventInterface;
use Google\CloudFunctions\FunctionsFramework;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Clients\MessagingApi\Model\PushMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

FunctionsFramework::http('main_http', 'main_http');
function main_http(ServerRequestInterface $request): ResponseInterface
{
    $log = new Logger('main_http');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function triggered with ' . AppConfig::getEnvironment() . ' environment.');

    $quotesController = new QuotesController();

    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    $log->info("{$method} {$path}");

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
        return new Response(404, [], 'Not Found');
    }
}

FunctionsFramework::cloudEvent('main_event', 'main_event');
function main_event(CloudEventInterface $event): void
{
    $log = new Logger('main_event');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function triggered with ' . AppConfig::getEnvironment() . ' environment.');

    $rawLineConfig = getenv('LINE_TOKENS_N_TARGETS');
    if (!$rawLineConfig) {
        $log->error('LINE_TOKENS_N_TARGETS environment variable is not set.');
        return;
    }

    $lineConfig = json_decode($rawLineConfig);
    $lineDeliverTarget = AppConfig::getLineDeliverTarget();

    if (!$lineConfig || !isset($lineConfig->tokens->$lineDeliverTarget) || !isset($lineConfig->target_ids->$lineDeliverTarget)) {
        $log->error("Failed to parse LINE_TOKENS_N_TARGETS or missing target configuration for '{$lineDeliverTarget}'.");
        return;
    }

    $client = new Client();
    $config = new Configuration();
    $config->setAccessToken($lineConfig->tokens->$lineDeliverTarget);
    $messagingApi = new MessagingApiApi(
        client: $client,
        config: $config,
    );

    $quoteList = new QuoteList();
    $quote = $quoteList->getRandomQuote();

    $messageText = $quote->getFormattedMessage();
    $log->info("Selected quote: {$messageText}");

    $textMessage = new TextMessage(['text' => $messageText]);
    $pushRequest = new PushMessageRequest([
        'to' => $lineConfig->target_ids->$lineDeliverTarget,
        'messages' => [$textMessage],
    ]);
    $messagingApi->pushMessage($pushRequest);

    $log->info('Message sent successfully!');
    $quoteList->incrementDeliveredCount((int)$quote->getNo());
}
