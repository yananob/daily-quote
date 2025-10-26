<?php

use Google\CloudFunctions\FunctionsFramework;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';

// Register the function with the Functions Framework.
FunctionsFramework::http('app', 'handle');

// The function that will be executed for each request.
function handle(Request $request)
{
    // Bootstrap the Laravel application.
    $app = require __DIR__.'/../bootstrap/app.php';

    // Handle the request and return the response.
    return $app->handle($request);
}