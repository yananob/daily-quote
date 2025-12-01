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

    public function testEdit()
    {
        $quote = new Quote(['no' => 1, 'author' => 'Author', 'text' => 'Text']);

        $quoteListMock = $this->createMock(QuoteList::class);
        $quoteListMock->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($quote);

        $this->bladeMock->expects($this->once())
            ->method('run')
            ->with('quotes.edit', ['quote' => $quote]);

        $controller = $this->createControllerWithMock($quoteListMock);

        $request = new ServerRequest('GET', '/quotes/edit/1');

        $response = $controller->edit($request, 1);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $quoteListMock = $this->createMock(QuoteList::class);
        $quoteListMock->expects($this->once())
            ->method('update')
            ->with(1, ['author' => 'New Author', 'text' => 'New Text']);

        $controller = $this->createControllerWithMock($quoteListMock);

        $request = new ServerRequest(
            'POST',
            '/quotes/update/1',
            [], // headers
            'author=New+Author&text=New+Text',
            '1.1',
            $_SERVER
        );
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request = $request->withParsedBody([
            'author' => 'New Author',
            'text' => 'New Text'
        ]);

        $response = $controller->update($request, 1);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $response->getHeaderLine('Location'));
    }

    public function testUpdateWithInvalidData()
    {
        $quote = new Quote(['no' => 1, 'author' => 'Author', 'text' => 'Text']);

        $quoteListMock = $this->createMock(QuoteList::class);
        $quoteListMock->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($quote);

        $this->bladeMock->expects($this->once())
            ->method('run')
            ->with('quotes.edit', [
                'quote' => $quote,
                'error' => 'Author and text cannot be empty.',
            ]);

        $controller = $this->createControllerWithMock($quoteListMock);

        $request = new ServerRequest(
            'POST',
            '/quotes/update/1',
            [], // headers
            'author=&text=New+Text',
            '1.1',
            $_SERVER
        );
        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request = $request->withParsedBody([
            'author' => '',
            'text' => 'New Text'
        ]);

        $response = $controller->update($request, 1);

        $this->assertEquals(400, $response->getStatusCode());
    }
}
