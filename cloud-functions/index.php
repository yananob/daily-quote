<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Google\CloudFunctions\FunctionsFramework;
use CloudEvents\V1\CloudEventInterface;

// Register the function with Functions Framework.
FunctionsFramework::cloudEvent('deliverQuote', 'deliverQuote');

use App\Quote;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

function deliverQuote(CloudEventInterface $event): void
{
    $log = fopen(getenv('LOGGER_OUTPUT') ?: 'php://stderr', 'wb');
    fwrite($log, "Function deliverQuote triggered.\\n");

    $httpClient = new CurlHTTPClient(getenv('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
    $bot = new LINEBot($httpClient, ['channelSecret' => getenv('LINE_BOT_CHANNEL_SECRET')]);

    $target = getenv('MYAPP_DELIVER_TARGET');
    if (empty($target)) {
        fwrite($log, "Please specify MYAPP_DELIVER_TARGET.\\n");
        return;
    }

    $quote = (new Quote())->getRandomMessage();
    fwrite($log, "Selected quote: {$quote}\\n");

    $textMessageBuilder = new TextMessageBuilder($quote);
    $response = $bot->pushMessage($target, $textMessageBuilder);

    if ($response->isSucceeded()) {
        fwrite($log, "Message sent successfully!\\n");
    } else {
        fwrite($log, "Failed to send message: " . $response->getRawBody() . "\\n");
    }
}
