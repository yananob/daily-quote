<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Google\CloudFunctions\FunctionsFramework;
use CloudEvents\V1\CloudEventInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use LINE\Clients\MessagingApi\Model\PushMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use Dotenv\Dotenv;
use App\QuoteList;

FunctionsFramework::cloudEvent('main_event', 'main_event');
function main_event(CloudEventInterface $event): void
{
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required(['LINE_BOT_CHANNEL_ACCESS_TOKEN', 'LINE_DELIVER_TARGET'])->notEmpty();

    $log = new Logger('deliverQuote');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function deliverQuote triggered.');

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
