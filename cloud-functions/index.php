<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Google\CloudFunctions\FunctionsFramework;
use CloudEvents\V1\CloudEventInterface;

// Register the function with Functions Framework.
FunctionsFramework::cloudEvent('deliverQuote', 'deliverQuote');

use App\QuoteList;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function deliverQuote(CloudEventInterface $event): void
{
    $log = new Logger('deliverQuote');
    $log->pushHandler(new StreamHandler('php://stderr'));
    $log->info('Function deliverQuote triggered.');

    $httpClient = new CurlHTTPClient(getenv('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
    $bot = new LINEBot($httpClient, ['channelSecret' => getenv('LINE_BOT_CHANNEL_SECRET')]);

    $target = getenv('MYAPP_DELIVER_TARGET');
    if (empty($target)) {
        $log->error('Please specify MYAPP_DELIVER_TARGET.');
        return;
    }

    $quote = (new QuoteList())->getRandomQuote();
    if ($quote === null) {
        $log->info('No quotes found.');
        return;
    }
    $message = $quote->getFormattedMessage();
    $log->info("Selected quote: {$message}");

    $textMessageBuilder = new TextMessageBuilder($message);
    $response = $bot->pushMessage($target, $textMessageBuilder);

    if ($response->isSucceeded()) {
        $log->info('Message sent successfully!');
    } else {
        $log->error('Failed to send message: ' . $response->getRawBody());
    }
}
