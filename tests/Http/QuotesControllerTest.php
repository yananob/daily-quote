<?php

declare(strict_types=1);

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use App\Http\QuotesController;
use App\QuoteList;
use App\Quote;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use eftec\bladeone\BladeOne;

class QuotesControllerTest extends TestCase
{
    private $bladeMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bladeMock = $this->createMock(BladeOne::class);
    }

    private function createControllerWithMock(QuoteList $quoteList): QuotesController
    {
        $controller = new QuotesController($quoteList);

        $reflector = new \ReflectionClass(QuotesController::class);
        $property = $reflector->getProperty('blade');
        $property->setAccessible(true);
        $property->setValue($controller, $this->bladeMock);

        return $controller;
    }

    public function test_編集画面が正しく表示されること(): void
    {
        $quote = new Quote(['no' => 1, 'author' => 'Author', 'message' => 'Message']);

        $quoteListMock = $this->createMock(QuoteList::class);
        $quoteListMock->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($quote);

        $this->bladeMock->expects($this->once())
            ->method('run')
            ->with('quotes.edit', ['quote' => $quote, 'page' => 2]);

        $controller = $this->createControllerWithMock($quoteListMock);

        $request = new ServerRequest('GET', '/quotes/edit/1?page=2');
        $request = $request->withQueryParams(['page' => '2']);

        $response = $controller->edit($request, 1);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_格言更新後に指定されたページ番号へリダイレクトされること(): void
    {
        $quoteListMock = $this->createMock(QuoteList::class);
        $quoteListMock->expects($this->once())
            ->method('update')
            ->with(1, [
                'author' => 'New Author',
                'message' => 'New Message',
                'source' => 'New Source',
                'source_link' => 'http://new.example.com'
            ]);

        $controller = $this->createControllerWithMock($quoteListMock);

        $request = new ServerRequest(
            'POST',
            '/quotes/update/1?page=2',
            [], // headers
            'author=New+Author&message=New+Message&source=New+Source&source_link=http://new.example.com',
            '1.1',
            $_SERVER
        );
        $request = $request->withQueryParams(['page' => '2']);
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request = $request->withParsedBody([
            'author' => 'New Author',
            'message' => 'New Message',
            'source' => 'New Source',
            'source_link' => 'http://new.example.com'
        ]);

        $response = $controller->update($request, 1);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/?page=2', $response->getHeaderLine('Location'));
    }

    public function test_不正データでの更新時にエラーとページ番号を保持して編集画面を再描画すること(): void
    {
        $quote = new Quote(['no' => 1, 'author' => 'Author', 'message' => 'Message']);

        $quoteListMock = $this->createMock(QuoteList::class);
        $quoteListMock->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($quote);

        $this->bladeMock->expects($this->once())
            ->method('run')
            ->with('quotes.edit', [
                'quote' => $quote,
                'page' => 2,
                'error' => 'Author and message cannot be empty.',
            ]);

        $controller = $this->createControllerWithMock($quoteListMock);

        $request = new ServerRequest(
            'POST',
            '/quotes/update/1?page=2',
            [], // headers
            'author=&message=New+Message',
            '1.1',
            $_SERVER
        );
        $request = $request->withQueryParams(['page' => '2']);
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request = $request->withParsedBody([
            'author' => '',
            'message' => 'New Message'
        ]);

        $response = $controller->update($request, 1);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_格言削除後に指定されたページ番号へリダイレクトされること(): void
    {
        $quoteListMock = $this->createMock(QuoteList::class);
        $quoteListMock->expects($this->once())
            ->method('delete')
            ->with(1);

        $controller = $this->createControllerWithMock($quoteListMock);

        $request = new ServerRequest('POST', '/quotes/delete/1?page=2');
        $request = $request->withQueryParams(['page' => '2']);

        $response = $controller->delete($request, 1);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/?page=2', $response->getHeaderLine('Location'));
    }
}
