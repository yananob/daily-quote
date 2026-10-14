<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
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

    /**
     * リクエストからページ番号を取得します（最小値は1）。
     *
     * @param ServerRequestInterface $request
     * @return int
     */
    private function getPageFromRequest(ServerRequestInterface $request): int
    {
        $queryParams = $request->getQueryParams();
        $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
        return max(1, $page);
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $page = $this->getPageFromRequest($request);

        $quotes = $this->quoteList->getListInPage($page, self::QUOTES_PER_PAGE);

        $statistics = $this->quoteList->getStatistics();
        $totalQuotes = $statistics['totalQuotes'];
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
            'statistics' => $statistics,
        ]);

        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }

    public function edit(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $page = $this->getPageFromRequest($request);

        $quote = $this->quoteList->find($id);

        if (!$quote) {
            return new Response(404, [], 'Not Found');
        }

        $body = $this->blade->run('quotes.edit', [
            'quote' => $quote,
            'page' => $page,
        ]);

        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }

    public function update(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $page = $this->getPageFromRequest($request);

        $data = (array)$request->getParsedBody();
        $author = trim((string)($data['author'] ?? ''));
        $message = trim((string)($data['message'] ?? ''));
        $source = trim((string)($data['source'] ?? ''));
        $sourceLink = trim((string)($data['source_link'] ?? ''));

        if ($author === '' || $message === '') {
            $quote = $this->quoteList->find($id);
            $body = $this->blade->run('quotes.edit', [
                'quote' => $quote,
                'page' => $page,
                'error' => 'Author and message cannot be empty.',
            ]);
            return new Response(400, ['Content-Type' => 'text/html'], $body);
        }

        $this->quoteList->update($id, [
            'author' => $author,
            'message' => $message,
            'source' => $source,
            'source_link' => $sourceLink,
        ]);

        return new Response(302, ['Location' => '/?page=' . $page]);
    }

    public function new(ServerRequestInterface $request): ResponseInterface
    {
        $body = $this->blade->run('quotes.new');
        return new Response(200, ['Content-Type' => 'text/html'], $body);
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = (array)$request->getParsedBody();
        $author = trim((string)($data['author'] ?? ''));
        $message = trim((string)($data['message'] ?? ''));
        $source = trim((string)($data['source'] ?? ''));
        $sourceLink = trim((string)($data['source_link'] ?? ''));

        if ($author === '' || $message === '') {
            $body = $this->blade->run('quotes.new', [
                'error' => 'Author and message cannot be empty.',
                'quote' => $data,
            ]);
            return new Response(400, ['Content-Type' => 'text/html'], $body);
        }

        $this->quoteList->create([
            'author' => $author,
            'message' => $message,
            'source' => $source,
            'source_link' => $sourceLink,
        ]);

        return new Response(302, ['Location' => '/']);
    }

    public function delete(ServerRequestInterface $request, int $id): ResponseInterface
    {
        $page = $this->getPageFromRequest($request);

        $this->quoteList->delete($id);

        return new Response(302, ['Location' => '/?page=' . $page]);
    }
}
