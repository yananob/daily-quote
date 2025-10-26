<?php

use Google\CloudFunctions\FunctionsFramework;
use App\Models\Quote;
use yananob\MyTools\Line;

require __DIR__.'/../vendor/autoload.php';

// Register the function with the Functions Framework.
FunctionsFramework::cloudEvent('deliverQuote', 'deliverQuote');

// The function that will be executed for each request.
function deliverQuote()
{
    // Bootstrap the Laravel application.
    $app = require __DIR__.'/../bootstrap/app.php';

    $target = env('MYAPP_DELIVER_TARGET');
    if (empty($target)) {
        throw new \Exception('Please specity MYAPP_DELIVER_TARGET.');
    }
    print("Sending daily-quote to {$target}\n");
    $line = new Line(base_path('config/line.json'));
    $line->sendPush(
        bot: $target,
        target: $target,
        message: Quote::randomMessage(),
    );
}