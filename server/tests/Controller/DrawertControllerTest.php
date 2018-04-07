<?php
declare(strict_types=1);

namespace Drawert\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

final class DrawertControllerTest extends TestCase
{
    public function testStartQuizReturnsListOfFiveEntries() {
        $controller = new DrawertController();

        $request = $this->prophesize(ServerRequestInterface::class);
        $returnedResponse = $controller->startQuiz($request->reveal(), new Response());

        $jsonResponse = json_decode((string)$returnedResponse->getBody(), true);
        $this->assertArrayHasKey('logos', $jsonResponse);
        $this->assertCount(5, $jsonResponse['logos']);
    }

    public function testStartQuizReturnsId() {
        $controller = new DrawertController();

        $request = $this->prophesize(ServerRequestInterface::class);
        $returnedResponse = $controller->startQuiz($request->reveal(), new Response());

        $jsonResponse = json_decode((string)$returnedResponse->getBody(), true);
        $this->assertArrayHasKey('id', $jsonResponse);
    }
}

