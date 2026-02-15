<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use App\AppConfig;
use App\QuoteList;

class QuotesController extends BaseController
{
    private const QUOTES_PER_PAGE = 20;
    private QuoteList $quoteList;

    public function __construct(?QuoteList $quoteList = null)
    {
        parent::__construct();
        $this->quoteList = $quoteList ?? new QuoteList();
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;

        $quotes = $this->quoteList->getListInPage($page, self::QUOTES_PER_PAGE);

        $totalQuotes = $this->quoteList->getTotalCount();
        $lastPage = (int)ceil($totalQuotes / self::QUOTES_PER_PAGE);

        $hasNextPage = count($quotes) > self::QUOTES_PER_PAGE;
        if ($hasNextPage) {
            array_pop($quotes); // remove the extra item
        }

        $body = $this->blade->run('quotes.index', [
            'quotes' => $quotes,
            'page' => $page,
            'hasNextPage' => $hasNextPage,
            'lastPage' => $lastPage,
        ]);

        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }

    public function edit(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $quote = $this->quoteList->find($id);

        if (!$quote) {
            return new Response(404, [], 'Not Found');
        }

        $body = $this->blade->run('quotes.edit', [
            'quote' => $quote,
        ]);

        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }

    public function update(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (empty($data['author']) || empty($data['message'])) {
            $quote = $this->quoteList->find($id);
            $body = $this->blade->run('quotes.edit', [
                'quote' => $quote,
                'error' => 'Author and message cannot be empty.',
            ]);
            return new Response(400, ['Content-Type' => 'text/html'], $body);
        }

        $this->quoteList->update($id, [
            'author' => $data['author'],
            'message' => $data['message'],
            'source' => $data['source'],
            'source_link' => $data['source_link'],
        ]);

        return new Response(302, ['Location' => AppConfig::getBasePath() . '/']);
    }

    public function new(ServerRequestInterface $request): ResponseInterface
    {
        $body = $this->blade->run('quotes.new');
        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (empty($data['author']) || empty($data['message'])) {
            $body = $this->blade->run('quotes.new', [
                'error' => 'Author and message cannot be empty.',
                'quote' => $data,
            ]);
            return new Response(400, ['Content-Type' => 'text/html'], $body);
        }

        $this->quoteList->create([
            'author' => $data['author'],
            'message' => $data['message'],
            'source' => $data['source'],
            'source_link' => $data['source_link'],
        ]);

        return new Response(302, ['Location' => AppConfig::getBasePath() . '/']);
    }

    public function delete(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $this->quoteList->delete($id);

        return new Response(302, ['Location' => AppConfig::getBasePath() . '/']);
    }
}
