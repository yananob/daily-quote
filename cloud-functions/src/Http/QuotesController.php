<?php

declare(strict_types=1);

namespace App\Http;

use App\QuoteList;
use EFTEC\BladeOne\BladeOne;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class QuotesController extends BaseController
{
    private const QUOTES_PER_PAGE = 20;

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;

        $quoteList = new QuoteList();
        $quotes = $quoteList->getQuotes($page, self::QUOTES_PER_PAGE);

        $hasNextPage = count($quotes) > self::QUOTES_PER_PAGE;
        if ($hasNextPage) {
            array_pop($quotes); // remove the extra item
        }

        $body = $this->blade->run('quotes.index', [
            'quotes' => $quotes,
            'page' => $page,
            'hasNextPage' => $hasNextPage,
        ]);

        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }
}
