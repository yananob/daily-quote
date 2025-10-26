<?php

use Google\CloudFunctions\FunctionsFramework;
use App\Models\Quote;
use yananob\MyTools\Line;

require __DIR__.'/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

// Register the function with the Functions Framework.
FunctionsFramework::cloudEvent('deliverQuote', 'deliverQuote');

// The function that will be executed for each request.
function deliverQuote()
{
    $target = config('myapp_deliver_target');
    if (empty($target)) {
        throw new \Exception('Please specity MYAPP_DELIVER_TARGET.');
    }
    print("Sending daily-quote to {$target}\n");
    $line = new Line(__DIR__ . '/../config/line.json');
    $line->sendPush(
        bot: $target,
        target: $target,
        message: Quote::randomMessage(),
    );
}