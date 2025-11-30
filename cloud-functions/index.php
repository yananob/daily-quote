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

FunctionsFramework::cloudEvent('deliverQuote', 'deliverQuote');
function deliverQuote(CloudEventInterface $event): void
{
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $log = new Logger('deliverQuote');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function deliverQuote triggered.');

    // $httpClient = new CurlHTTPClient(getenv('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
    // $bot = new LINEBot($httpClient, ['channelSecret' => getenv('LINE_BOT_CHANNEL_SECRET')]);
    $client = new \GuzzleHttp\Client();
    $config = new \LINE\Clients\MessagingApi\Configuration();
    $config->setAccessToken(getenv('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
    $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
        client: $client,
        config: $config,
    );

    $target = getenv('MYAPP_DELIVER_TARGET');
    if (empty($target)) {
        throw new \RuntimeException('Environment variable MYAPP_DELIVER_TARGET is not set.');
    }

    $quote = (new QuoteList())->getRandomQuote();

    $message = $quote->getFormattedMessage();
    $log->info("Selected quote: {$message}");

    // $textMessageBuilder = new TextMessageBuilder($message);
    // $response = $bot->pushMessage($target, $textMessageBuilder);
    $message = new TextMessage(['text' => $message]);
    $request = new PushMessageRequest([
        'to' => $target,
        'messages' => [$message],
    ]);
    $response = $messagingApi->pushMessage($request);

    // if ($response->isSucceeded()) {
    $log->info('Message sent successfully!');
    // } else {
    //     $log->error('Failed to send message: ' . $response->getRawBody());
    // }
}
